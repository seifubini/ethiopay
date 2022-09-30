<?php

namespace App\library;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Exception;
use Hashids\Hashids;
use App\Models\Setting;
use Illuminate\Support\Facades\File;

class CommonFunction extends Controller {

    /**
     * Input Validator
     *
     */
    public static function inputValidator($input, $rules, $messages) {
        if (!$messages)
            $messages = [];
        $validator = Validator::make($input, $rules, $messages);
        if ($validator->fails()) {
            $message = $validator->errors();

            $validationAllErrorsArray = $validator->errors()->all();
            $validationAllErrorsStr = implode("<br>", $validationAllErrorsArray);

            $code_message = $validationAllErrorsStr;
            if (is_object($message)) {
                $message = $message->toArray();
            }
            $data = array(
                'status' => false,
                'message' => $code_message,
                'errors' => $message
            );
            return response()->json($data);
        }
    }

    /**
     * Generate Random File Name
     *
     */
    public static function generateRandomFileName() {
        return time() . str_random(10);
    }

    /**
     * Delete File 
     *
     */
    public static function deleteFile($fileLocation) {
        if (File::exists($fileLocation)) {
            File::delete($fileLocation);
        }
        return true;
    }

    /**
     * Generate Verify Code
     *
     */
    public static function generateVerifyCode() {
        $text = '';
        $length = 4;
        $possible = "123456789";
        for ($i = 0; $i < $length; $i++) {
            $text .= substr($possible, rand(0, 8), 1);
        }
        return $text;
    }

    public static function encodeForID($str) {
        $hashids = new Hashids();
        return $hashids->encode($str);
    }

    public static function decodeForID($str) {
        $hashids = new Hashids();
        $decodedStrArr = $hashids->decode($str);
        if ($decodedStrArr && is_array($decodedStrArr) && count($decodedStrArr) > 0) {
            return $decodedStrArr[0];
        }
        return '';
    }

    public static function encodeForFederalID($str) {
        if ($str) {
            for ($i = 0; $i < 7; $i++) {
                $str = strrev(base64_encode($str));
            }
            return $str;
        }
        return '';
    }

    public static function decodeForFederalID($str) {
        if ($str) {
            for ($i = 0; $i < 7; $i++) {
                $str = base64_decode(strrev($str));
            }
            return $str;
        }
        return '';
    }

    public static function getSettingByKey($key) {
        $setting = Setting::where('key', $key)->first();
        if ($setting) {
            return $setting->value;
        }
        return '';
    }

    /**
     * Generate Verify Code
     *
     */
//    public static function generateTransactionId() {
//        $text = '';
//        $length = 6;
//        $possible = "123456789";
//        for ($i = 0; $i < $length; $i++) {
//            $text .= substr($possible, rand(0, 8), 1);
//        }
//        return $text;
//    }

    public static function generateTransactionId($transactionId) {
        return sprintf("%06d", $transactionId);
    }

}
