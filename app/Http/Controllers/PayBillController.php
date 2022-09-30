<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Address;
use App\Models\UidLookup;
use App\Models\ServiceType;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Mail\ServicePayedMail;
use App\library\CommonFunction;
use App\Models\ServiceProvider;
use App\Mail\ServicePayedFailMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Jobs\TransactionCompleteTwillioMsgToDebtorJob;

class PayBillController extends Controller {

    public function index(Request $request, $id) {
        $user_id = auth()->guard('web')->user()->id;
        $serviceProviders = ServiceProvider::where('service_type_id', $id)->orderBy('provider_name')->get();
        $address = Address::with(['cityData'])->where('user_id', $user_id)->first();
        $payment_Fee_In_Percentage = (float) CommonFunction::getSettingByKey('Payment_Fee_In_Percentage');
        $paymentMethods = PaymentMethod::where('user_id', $user_id)->get();

        $viewData = [
            'service_type_id' => $id,
            'serviceProviders' => $serviceProviders,
            'address' => $address,
            'payment_Fee_In_Percentage' => $payment_Fee_In_Percentage,
            'paymentMethods' => $paymentMethods
        ];
        return view('payBill.index', $viewData);
    }

    public function payBill2(Request $request) {
        $user_id = auth()->guard('web')->user()->id;
        $serviceTypes = ServiceType::orderBy('service_name')->get();
        $payment_Fee_In_Percentage = (float) CommonFunction::getSettingByKey('Payment_Fee_In_Percentage');
        $paymentMethods = PaymentMethod::where('user_id', $user_id)->get();

        $viewData = [
            'serviceTypes' => $serviceTypes,
            'payment_Fee_In_Percentage' => $payment_Fee_In_Percentage,
            'paymentMethods' => $paymentMethods
        ];
        return view('payBill.index2', $viewData);
    }

    public function payBillStore(Request $request) {
        $input = $request->all();
        $rules = config('input_validation.rules.payBillStore');
        $messages = config('input_validation.messages.payBillStore');

        $apiValidator = CommonFunction::inputValidator($input, $rules, $messages);
        if ($apiValidator)
            return $apiValidator;

        $uIdLookup = UidLookup::findOrFail($input['uid_lookup_id']);

        if (!$uIdLookup) {
            $data = array(
                'status' => false,
                'message' => 'UID is invalid.',
            );
            return response()->json($data);
        }

        $user_id = auth()->guard('web')->user()->id;
        $paymentMethod = PaymentMethod::where('id', $input['paymentMethodId'])->where('user_id', $user_id)->first();
        $serviceType = ServiceType::where('id', $uIdLookup['service_type_id'])->first();
        //$payment_Fee_In_Percentage = (float) CommonFunction::getSettingByKey('Payment_Fee_In_Percentage');
        $payment_Fee_In_Percentage = (float) $serviceType->payment_fee_in_percentage;
        $payBillAmount = round((float) $uIdLookup['amount'], 2);

        if ($paymentMethod && $paymentMethod->method_type == 'card') {
            $stripe_card_id = $paymentMethod->stripe_card_id;
            $payBillPaymentFee = round((($payBillAmount * $payment_Fee_In_Percentage) / 100), 2);
            $payBillAmountTotal = round($payBillAmount + $payBillPaymentFee, 2);
            $createChargeFromCardRes = $this->createChargeFromCard($stripe_card_id, $payBillAmountTotal, $input);

            if ($createChargeFromCardRes['status'] == true) {
                $customerCardChargeRes = $createChargeFromCardRes['customerCardCharge'];
                $payBillAmountStripe = ($customerCardChargeRes->amount / 100);

                if ($payBillAmountStripe == $payBillAmountTotal) {
                    $transaction = new Transaction();
                    $transaction->user_id = $user_id;
                    $transaction->service_provider_id = $input['serviceProviderId'];
                    $transaction->payment_method_id = $input['paymentMethodId'];
                    $transaction->customer_service_number = $uIdLookup['uid'];
                    $transaction->customer_pay_amount = $payBillAmount;
                    $transaction->commision_in_percentage = $payment_Fee_In_Percentage;
                    $transaction->commision_amount = $payBillPaymentFee;
                    $transaction->total_pay_amount = $payBillAmountTotal;
                    $transaction->transaction_status = 'succeeded';
                    $transaction->stripe_transaction_id = $customerCardChargeRes->id;
                    $transaction->stripe_transaction_response = json_encode($customerCardChargeRes);
                    $transaction->uid_lookup_id = $uIdLookup['id'];
                    $transaction->service_type_id = $uIdLookup['service_type_id'];
                    $transaction->debtor_firstname = $uIdLookup['debtor_firstname'];
                    $transaction->debtor_lastname = $uIdLookup['debtor_lastname'];
                    $transaction->debtor_city = $uIdLookup['debtor_city'];
                    $transaction->debtor_phone_code = $input['debtor_phone_code'];
                    $transaction->debtor_phone_number = $input['debtor_phone_number'];
                    $transaction->cut_off_date = $uIdLookup['cut_off_date'];
                    $transaction->save();
                    $transaction->random_transaction_id = CommonFunction::generateTransactionId($transaction->id);
                    $transaction->save();

                    $user = User::where('id', $transaction->user_id)->first();
                    $serviceProvider = ServiceProvider::where('id', $transaction->service_provider_id)->first();
                    // return new \App\Mail\ServicePayedMail($user, $transaction, $serviceProvider);
                    $emailQueueMessage = (new ServicePayedMail($user, $transaction, $serviceProvider))->onQueue('emails');
                    Mail::to($user->email, $user->fullname)->queue($emailQueueMessage);

                    TransactionCompleteTwillioMsgToDebtorJob::dispatch($transaction)->onQueue('twilio');
                    //$request->session()->flash('successAlert', 'Bill payed successfully.');
                    $data = array(
                        'status' => true,
                        'message' => 'Bill payed successfully.',
                        'transaction_id' => $transaction->id
                    );
                    return response()->json($data);
                } else {
                    $data = array('status' => false, 'message' => 'Something went wrong with your transaction, Please contact administrator.');
                    return response()->json($data);
                }
            } else {
                $transaction = new Transaction();
                $transaction->user_id = $user_id;
                $transaction->service_provider_id = $input['serviceProviderId'];
                $transaction->payment_method_id = $input['paymentMethodId'];
                $transaction->customer_service_number = $uIdLookup['uid'];
                $transaction->customer_pay_amount = $payBillAmount;
                $transaction->commision_in_percentage = $payment_Fee_In_Percentage;
                $transaction->commision_amount = $payBillPaymentFee;
                $transaction->total_pay_amount = $payBillAmountTotal;
                $transaction->transaction_status = 'failed';
                $transaction->stripe_transaction_id = $createChargeFromCardRes['customerCardChargeId'];
                $transaction->stripe_transaction_response = $createChargeFromCardRes['customerCardChargeJson'];
                $transaction->uid_lookup_id = $uIdLookup['id'];
                $transaction->service_type_id = $uIdLookup['service_type_id'];
                $transaction->debtor_firstname = $uIdLookup['debtor_firstname'];
                $transaction->debtor_lastname = $uIdLookup['debtor_lastname'];
                $transaction->debtor_city = $uIdLookup['debtor_city'];
                $transaction->debtor_phone_code = $input['debtor_phone_code'];
                $transaction->debtor_phone_number = $input['debtor_phone_number'];
                $transaction->cut_off_date = $uIdLookup['cut_off_date'];
                $transaction->save();
                $transaction->random_transaction_id = CommonFunction::generateTransactionId($transaction->id);
                $transaction->save();

                $user = User::where('id', $transaction->user_id)->first();
                $serviceProvider = ServiceProvider::where('id', $transaction->service_provider_id)->first();
                    
                $emailQueueMessage = (new ServicePayedFailMail($user, $transaction, $serviceProvider))->onQueue('emails');
                Mail::to($user->email, $user->fullname)->queue($emailQueueMessage);

                $data = array(
                    'status' => false,
                    'message' => $createChargeFromCardRes['message'],
                );
                return response()->json($data);
            }
        } else {
            $data = array('status' => false, 'message' => 'Payment method not supported.');
            return response()->json($data);
        }
    }

    public function createChargeFromCard($customerCardId, $payBillAmountTotal, $input) {
        try {
        //    $payBillAmountTotal = 0.3;
            // Charge Customer
            $chargeDescription = "Charge for bill payment"
                    . ",\n Customer Id: " . auth()->guard('web')->user()->id
                    . ",\n Customer Service Number: " . $input['customer_service_number']
                    . ",\n Service Provider Id: " . $input['serviceProviderId'];

            $customerCardCharge = \Stripe\Charge::create(array(
                        "amount" => $payBillAmountTotal * 100,
                        "currency" => "usd",
                        "customer" => auth()->guard('web')->user()->stripe_customer_id,
                        "source" => $customerCardId,
                        "description" => $chargeDescription
            ));

            if ($customerCardCharge->status == 'succeeded') {
                $data = array(
                    'status' => true,
                    'message' => 'card charge created successfully.',
                    'customerCardCharge' => $customerCardCharge
                );
                return $data;
            } else {
                $data = array(
                    'status' => false,
                    'message' => 'insufficient funds in your account.',
                    'customerCardChargeId' => $customerCardCharge->id,
                    'customerCardChargeJson' => json_encode($customerCardCharge)
                );
                return $data;
            }
        } catch (\Stripe\Error\RateLimit $e) {
            // Too many requests made to the API too quickly
            $stripeResponseBody = $e->getJsonBody();
            $stripeResponseBodyErr = $stripeResponseBody['error'];
            Log::info($stripeResponseBodyErr);
            Log::info(json_encode($stripeResponseBodyErr));

            $data = array(
                'status' => false,
                'message' => $stripeResponseBodyErr['message'],
                'customerCardChargeId' => '',
                'customerCardChargeJson' => json_encode($stripeResponseBodyErr)
            );
            return $data;
        } catch (\Stripe\Error\InvalidRequest $e) {
            // Invalid parameters were supplied to Stripe's API
            $stripeResponseBody = $e->getJsonBody();
            $stripeResponseBodyErr = $stripeResponseBody['error'];
            Log::info($stripeResponseBodyErr);
            Log::info(json_encode($stripeResponseBodyErr));

            $data = array(
                'status' => false,
                'message' => $stripeResponseBodyErr['message'],
                'customerCardChargeId' => '',
                'customerCardChargeJson' => json_encode($stripeResponseBodyErr)
            );
            return $data;
        } catch (\Stripe\Error\Authentication $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            $stripeResponseBody = $e->getJsonBody();
            $stripeResponseBodyErr = $stripeResponseBody['error'];
            Log::info($stripeResponseBodyErr);
            Log::info(json_encode($stripeResponseBodyErr));

            $data = array(
                'status' => false,
                'message' => $stripeResponseBodyErr['message'],
                'customerCardChargeId' => '',
                'customerCardChargeJson' => json_encode($stripeResponseBodyErr)
            );
            return $data;
        } catch (\Stripe\Error\ApiConnection $e) {
            // Network communication with Stripe failed
            $stripeResponseBody = $e->getJsonBody();
            $stripeResponseBodyErr = $stripeResponseBody['error'];
            Log::info($stripeResponseBodyErr);
            Log::info(json_encode($stripeResponseBodyErr));

            $data = array(
                'status' => false,
                'message' => $stripeResponseBodyErr['message'],
                'customerCardChargeId' => '',
                'customerCardChargeJson' => json_encode($stripeResponseBodyErr)
            );
            return $data;
        } catch (\Stripe\Error\Base $e) {
            $stripeResponseBody = $e->getJsonBody();
            $stripeResponseBodyErr = $stripeResponseBody['error'];
            Log::info($stripeResponseBodyErr);
            Log::info(json_encode($stripeResponseBodyErr));

            $data = array(
                'status' => false,
                'message' => $stripeResponseBodyErr['message'],
                'customerCardChargeId' => '',
                'customerCardChargeJson' => json_encode($stripeResponseBodyErr)
            );
            return $data;
        } catch (Exception $e) {
            $stripeResponseBody = $e->getMessage();
            Log::info($stripeResponseBody);

            $data = array(
                'status' => false,
                'message' => $stripeResponseBody,
                'customerCardChargeId' => '',
                'customerCardChargeJson' => json_encode(array('type' => 'Exception', 'message' => $stripeResponseBody))
            );
            return $data;
        }
    }

    public function payBillSuccess(Request $request, $id) {
        $transaction = Transaction::find($id);

        $viewData = [
            'transaction' => $transaction,
        ];
        return view('payBill.success', $viewData);
    }

    public function payBillFailed(Request $request) {
        $viewData = [
        ];
        return view('payBill.failed', $viewData);
    }

}
