<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use App\library\CommonFunction;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller {

    private $stripeCustomer;
    private $stripe_customer_id;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $user_id = auth()->guard('web')->user()->id;
        $paymentMethods = PaymentMethod::where('user_id', $user_id)->get();
        $viewData = [
            'paymentMethods' => $paymentMethods
        ];
        return view('paymentMethod.index', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('paymentMethod.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $input = $request->all();
        $rules = config('input_validation.rules.paymentMethodStore');
        $messages = config('input_validation.messages.paymentMethodStore');

        $apiValidator = CommonFunction::inputValidator($input, $rules, $messages);
        if ($apiValidator)
            return $apiValidator;
        
        $user_id = auth()->guard('web')->user()->id;

        if ($input['paymentMethodType'] == 'card') {
            $this->stripe_customer_id = auth()->guard('web')->user()->stripe_customer_id;
            $this->stripeCustomer = \Stripe\Customer::retrieve($this->stripe_customer_id);

            $customerChargeRefundJson = '';
            $tokenRetrieveRes = \Stripe\Token::retrieve($input['stripe_token']);
            //dd($tokenRetrieveRes);
            if (!$tokenRetrieveRes) {
                $data = array(
                    'status' => false,
                    'message' => 'Something went wrong with your card.',
                );
                return response()->json($data);
            }

            $fingerprint = $tokenRetrieveRes->card->fingerprint;

            $isCardAlreadyExists = PaymentMethod::where('user_id', $user_id)->where('stripe_card_fingerprint', $fingerprint)->exists();
            if ($isCardAlreadyExists) {
                $data = array(
                    'status' => false,
                    'message' => 'Card already exists.',
                );
                return response()->json($data);
            }
            try {
                // Create Card
                $customerCardTokenId = $input['stripe_token'];
                $customerCard = $this->stripeCustomer->sources->create(array("source" => $customerCardTokenId));
                $customerCardJson = json_encode($customerCard);
                //dd($customerCard);
                $customerCardChargeRes = $this->createCardValidationCharge($customerCard);
                if ($customerCardChargeRes['status'] == true) {
                    $customerCardCharge = $customerCardChargeRes['customerCardCharge'];
                    $customerCardChargeJson = json_encode($customerCardCharge);

                    $customerChargeRefundRes = $this->refundCardValidationCharge($customerCardCharge);
                    if ($customerChargeRefundRes['status'] == true) {
                        $customerChargeRefund = $customerChargeRefundRes['customerChargeRefund'];
                        $customerChargeRefundJson = json_encode($customerChargeRefund);
                    }

                    $paymentMethod = new PaymentMethod();
                    $paymentMethod->user_id = $user_id;
                    $paymentMethod->method_type = 'card';
                    $paymentMethod->card_type = $customerCard->brand;
                    $paymentMethod->name_on_card = $customerCard->name;
                    $paymentMethod->card_number = $customerCard->last4;
                    $paymentMethod->card_expiry_month = $customerCard->exp_month;
                    $paymentMethod->card_expiry_year = $customerCard->exp_year;
                    $paymentMethod->stripe_card_id = $customerCard->id;
                    $paymentMethod->stripe_card_fingerprint = $customerCard->fingerprint;
                    $paymentMethod->save();
                } else {
                    $this->stripeCustomer->sources->retrieve($customerCard->id)->delete();

                    $data = array(
                        'status' => false,
                        'message' => 'insufficient funds in your account.',
                        'customerCardChargeRes' => $customerCardChargeRes
                    );
                    return response()->json($data);
                }
            } catch (\Stripe\Error\RateLimit $e) {
                // Too many requests made to the API too quickly
                $stripeResponseBody = $e->getJsonBody();
                $stripeResponseBodyErr = $stripeResponseBody['error'];
                Log::info($stripeResponseBodyErr);

                $data = array('status' => false, 'message' => $stripeResponseBodyErr['message']);
                return response()->json($data);
            } catch (\Stripe\Error\InvalidRequest $e) {
                // Invalid parameters were supplied to Stripe's API
                $stripeResponseBody = $e->getJsonBody();
                $stripeResponseBodyErr = $stripeResponseBody['error'];
                Log::info($stripeResponseBodyErr);

                $data = array('status' => false, 'message' => $stripeResponseBodyErr['message']);
                return response()->json($data);
            } catch (\Stripe\Error\Authentication $e) {
                // Authentication with Stripe's API failed
                // (maybe you changed API keys recently)
                $stripeResponseBody = $e->getJsonBody();
                $stripeResponseBodyErr = $stripeResponseBody['error'];
                Log::info($stripeResponseBodyErr);

                $data = array('status' => false, 'message' => $stripeResponseBodyErr['message']);
                return response()->json($data);
            } catch (\Stripe\Error\ApiConnection $e) {
                // Network communication with Stripe failed
                $stripeResponseBody = $e->getJsonBody();
                $stripeResponseBodyErr = $stripeResponseBody['error'];
                Log::info($stripeResponseBodyErr);

                $data = array('status' => false, 'message' => $stripeResponseBodyErr['message']);
                return response()->json($data);
            } catch (\Stripe\Error\Base $e) {
                $stripeResponseBody = $e->getJsonBody();
                $stripeResponseBodyErr = $stripeResponseBody['error'];
                Log::info($stripeResponseBodyErr);

                $data = array('status' => false, 'message' => $stripeResponseBodyErr['message']);
                return response()->json($data);
            } catch (Exception $e) {
                $stripeResponseBody = $e->getMessage();
                $data = array('status' => false, 'message' => $stripeResponseBody);
                return response()->json($data);
            }

            $request->session()->flash('successAlert', 'Payment method added successfully.');
            $data = array('status' => true, 'message' => 'Payment method added successfully.');
            return response()->json($data);
        } else {
            $data = array(
                'status' => false,
                'message' => 'Something went wrong with your payment method.',
            );
            return response()->json($data);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request) {
        $paymentMethod = PaymentMethod::destroy($id);
        $data = array('status' => true, 'message' => 'Card deleted successfully.');
        return $data;
    }

    public function createCardValidationCharge($customerCard) {
        try {
            // Charge Customer
            $customerCardId = $customerCard->id;
            $customerCardCharge = \Stripe\Charge::create(array(
                        "amount" => 0.5 * 100,
                        "currency" => "usd",
                        "customer" => $this->stripe_customer_id,
                        "source" => $customerCardId,
                        "description" => "Charge for validate card for customer id : " . auth()->guard('web')->user()->id
            ));

            if ($customerCardCharge->status == 'succeeded') {
                $data = array(
                    'status' => true,
                    'message' => 'card charge created successfully',
                    'customerCardCharge' => $customerCardCharge
                );
                return $data;
            } else {
                $data = array(
                    'status' => false,
                    'message' => 'insufficient funds in your account.',
                    'customerCardCharge' => $customerCardCharge
                );
                return $data;
            }
        } catch (\Stripe\Error\RateLimit $e) {
            // Too many requests made to the API too quickly
            $stripeResponseBody = $e->getJsonBody();
            $stripeResponseBodyErr = $stripeResponseBody['error'];
            Log::info($stripeResponseBodyErr);

            $data = array('status' => false, 'message' => $stripeResponseBodyErr['message']);
            return $data;
        } catch (\Stripe\Error\InvalidRequest $e) {
            // Invalid parameters were supplied to Stripe's API
            $stripeResponseBody = $e->getJsonBody();
            $stripeResponseBodyErr = $stripeResponseBody['error'];
            Log::info($stripeResponseBodyErr);

            $data = array('status' => false, 'message' => $stripeResponseBodyErr['message']);
            return $data;
        } catch (\Stripe\Error\Authentication $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            $stripeResponseBody = $e->getJsonBody();
            $stripeResponseBodyErr = $stripeResponseBody['error'];
            Log::info($stripeResponseBodyErr);

            $data = array('status' => false, 'message' => $stripeResponseBodyErr['message']);
            return $data;
        } catch (\Stripe\Error\ApiConnection $e) {
            // Network communication with Stripe failed
            $stripeResponseBody = $e->getJsonBody();
            $stripeResponseBodyErr = $stripeResponseBody['error'];
            Log::info($stripeResponseBodyErr);

            $data = array('status' => false, 'message' => $stripeResponseBodyErr['message']);
            return $data;
        } catch (\Stripe\Error\Base $e) {
            $stripeResponseBody = $e->getJsonBody();
            $stripeResponseBodyErr = $stripeResponseBody['error'];
            Log::info($stripeResponseBodyErr);

            $data = array('status' => false, 'message' => $stripeResponseBodyErr['message']);
            return $data;
        } catch (Exception $e) {
            $stripeResponseBody = $e->getMessage();
            $data = array('status' => false, 'message' => $stripeResponseBody);
            return $data;
        }
    }

    public function refundCardValidationCharge($customerCardCharge) {
        try {
            // Refund
            $customerChargeRefund = \Stripe\Refund::create(array(
                        "charge" => $customerCardCharge->id
            ));
            $data = array(
                'status' => true,
                'message' => 'card charge refund successfully',
                'customerChargeRefund' => $customerChargeRefund
            );
            return $data;
        } catch (\Stripe\Error\RateLimit $e) {
            // Too many requests made to the API too quickly
            $stripeResponseBody = $e->getJsonBody();
            $stripeResponseBodyErr = $stripeResponseBody['error'];
            Log::info($stripeResponseBodyErr);

            $data = array('status' => false, 'message' => $stripeResponseBodyErr['message']);
            return $data;
        } catch (\Stripe\Error\InvalidRequest $e) {
            // Invalid parameters were supplied to Stripe's API
            $stripeResponseBody = $e->getJsonBody();
            $stripeResponseBodyErr = $stripeResponseBody['error'];
            Log::info($stripeResponseBodyErr);

            $data = array('status' => false, 'message' => $stripeResponseBodyErr['message']);
            return $data;
        } catch (\Stripe\Error\Authentication $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            $stripeResponseBody = $e->getJsonBody();
            $stripeResponseBodyErr = $stripeResponseBody['error'];
            Log::info($stripeResponseBodyErr);

            $data = array('status' => false, 'message' => $stripeResponseBodyErr['message']);
            return $data;
        } catch (\Stripe\Error\ApiConnection $e) {
            // Network communication with Stripe failed
            $stripeResponseBody = $e->getJsonBody();
            $stripeResponseBodyErr = $stripeResponseBody['error'];
            Log::info($stripeResponseBodyErr);

            $data = array('status' => false, 'message' => $stripeResponseBodyErr['message']);
            return $data;
        } catch (\Stripe\Error\Base $e) {
            $stripeResponseBody = $e->getJsonBody();
            $stripeResponseBodyErr = $stripeResponseBody['error'];
            Log::info($stripeResponseBodyErr);

            $data = array('status' => false, 'message' => $stripeResponseBodyErr['message']);
            return $data;
        } catch (Exception $e) {
            $stripeResponseBody = $e->getMessage();
            $data = array('status' => false, 'message' => $stripeResponseBody);
            return $data;
        }
    }

}
