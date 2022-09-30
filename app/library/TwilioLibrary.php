<?php

namespace App\library;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Log;

/* Twilio */
$TWILIO_SID = env('TWILIO_SID');
$TWILIO_TOKEN = env('TWILIO_TOKEN');
$twilioClient = new \Twilio\Rest\Client($TWILIO_SID, $TWILIO_TOKEN);
config(['ethiopay.twilio.client' => $twilioClient]);

class TwilioLibrary extends Controller {

    /**
     * Check phone no. is valid or not
     *
     */
    public static function isPhoneNoIsValid($phoneNo) {
        try {
            $twilioClient = config("ethiopay.twilio.client");
            $twilioClientLookupRes = $twilioClient->lookups
                    ->phoneNumbers($phoneNo)
                    ->fetch(
                    array("type" => "carrier")
            );
            if ($twilioClientLookupRes && $twilioClientLookupRes->carrier['type'] == 'mobile' && $twilioClientLookupRes->phoneNumber == $phoneNo) {
                return true;
            }
            return false;
        } catch (\Twilio\Exceptions\RestException $e) {
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * $number phone number
     * $body subject + \n + message
     */
    public static function SendSMS($phonenumber, $body) {
        try {
            if(!isset($phonenumber) || !isset($body)) {
                return false;
            }
            $twilioClient = config("ethiopay.twilio.client");
            $sms = $twilioClient->account->messages->create($phonenumber,array('from' => env('TWILIO_PHONE_NO'),'body' => $body));            
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
