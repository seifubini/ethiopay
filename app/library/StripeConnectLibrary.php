<?php

namespace App\library;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use App\Models\Driver;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;

class StripeConnectLibrary extends Controller {

    /**
     * Create Connect Account For Driver
     *
     */
    public static function createConnectAccountForDriver($driverConnectData) {
        $pkdrid = $driverConnectData['pkdrid'];
        $REMOTE_ADDR = $driverConnectData['REMOTE_ADDR'];
        
//        try {
//            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY', ''));
//            \Stripe\Stripe::setApiVersion(env('STRIPE_API_VERSION', ''));
//        } catch (Exception $e) {
//            $stripeResponseBody = $e->getJsonBody();
//            $stripeResponseBodyErr = $stripeResponseBody['error'];
//            Log::info($stripeResponseBodyErr);
//        }
        
        try {
            $driver = Driver::active()->conditionForStripeConnect()->where('pkdrid', $pkdrid)->with(['driverBankAccountData', 'countryData', 'stateData'])
                        ->first();
            
            if(!$driver){
                return ['status' => false, 'message' => 'No need to create connect account for driver'];
            }
            
            /* Create connect account for driver */
            $driverConnectAccount = \Stripe\Account::create(array(
                        "type" => "custom",
                        "country" => "US",
                        "email" => $driver->email
            ));
            $driver->stripeconnectaccountid = $driverConnectAccount->id;
            $driver->save();

            /* Accept term of service */
            $driverConnectAccount->tos_acceptance->date = Carbon::now('UTC')->timestamp;
            $driverConnectAccount->tos_acceptance->ip = $REMOTE_ADDR;
            $driverConnectAccount->save();
            
            /* Add legal entity update profile data */
            $driverConnectAccount->legal_entity->type = "individual";
            $driverConnectAccount->legal_entity->first_name = $driver->fname;
            $driverConnectAccount->legal_entity->last_name = $driver->lname;
            $driverConnectAccount->save();
            
            $driverDobArray = explode('-', $driver->dob);
            if($driverDobArray[2] != '00' && $driverDobArray[1] != '00' && $driverDobArray[0] != '0000'){
                $currentUtcDtObj = Carbon::createFromFormat('Y-m-d', Carbon::today('UTC')->format('Y-m-d'), 'UTC');
                $dobDtObj = Carbon::createFromFormat('Y-m-d', $driver->dob, 'UTC');
                $isDobPastDt = $currentUtcDtObj->gte($dobDtObj);
                if($isDobPastDt){
                    $driverConnectAccount->legal_entity->dob = array('day' => $driverDobArray[2], 'month' => $driverDobArray[1], 'year' => $driverDobArray[0]);
                }
            }
            $driverConnectAccount->save();
            return ['status' => true, 'message' => 'Driver stripe connect account created successfully.'];
        } catch (\Stripe\Error\RateLimit $e) {
            // Too many requests made to the API too quickly
            $stripeResponseBody = $e->getJsonBody();
            $stripeResponseBodyErr = $stripeResponseBody['error'];
            Log::info($stripeResponseBodyErr);
            return ['status' => false, 'message' => $stripeResponseBodyErr['message']];
        } catch (\Stripe\Error\InvalidRequest $e) {
            // Invalid parameters were supplied to Stripe's API
            $stripeResponseBody = $e->getJsonBody();
            $stripeResponseBodyErr = $stripeResponseBody['error'];
            Log::info($stripeResponseBodyErr);
            return ['status' => false, 'message' => $stripeResponseBodyErr['message']];
        } catch (\Stripe\Error\Authentication $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            $stripeResponseBody = $e->getJsonBody();
            $stripeResponseBodyErr = $stripeResponseBody['error'];
            Log::info($stripeResponseBodyErr);
            return ['status' => false, 'message' => $stripeResponseBodyErr['message']];
        } catch (\Stripe\Error\ApiConnection $e) {
            // Network communication with Stripe failed
            $stripeResponseBody = $e->getJsonBody();
            $stripeResponseBodyErr = $stripeResponseBody['error'];
            Log::info($stripeResponseBodyErr);
            return ['status' => false, 'message' => $stripeResponseBodyErr['message']];
        } catch (\Stripe\Error\Base $e) {
            $stripeResponseBody = $e->getJsonBody();
            $stripeResponseBodyErr = $stripeResponseBody['error'];
            Log::info($stripeResponseBodyErr);
            return ['status' => false, 'message' => $stripeResponseBodyErr['message']];
        } catch (Exception $e) {
            $stripeResponseBody = $e->getJsonBody();
            $stripeResponseBodyErr = $stripeResponseBody['error'];
            Log::info($stripeResponseBodyErr);
            return ['status' => false, 'message' => $stripeResponseBodyErr['message']];
        }
    }
}
