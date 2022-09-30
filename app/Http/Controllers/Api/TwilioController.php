<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\library\TwilioLibrary;
use App\Models\User;
use App\Models\AdminUserMessage;

class TwilioController extends Controller {

    public function __construct() {
        
    }

    public function msgReceivedAtTwilio(Request $request) {
        $input = $request->all();
//        $input['From'] = '+919904250770';
        if (isset($input['From']) && !empty($input['From'])) {
            $user = User::where(DB::raw('CONCAT(phone_code, phone_number)'), $input['From'])->first();
            if ($user) {
                $adminUserMessage = new AdminUserMessage();
                $adminUserMessage->user_id = $user->id;
                $adminUserMessage->admin_id = 0;
                $adminUserMessage->sent_by = 'user';
                $adminUserMessage->message = $input['Body'];
                $adminUserMessage->save();
                $resData = array(
                    'status' => true,
                    'message' => 'Message received successfully.',
                );
                return response()->json($resData);
            } else {
                $resData = array('status' => false, 'message' => 'User with from number not found.');
                return response()->json($resData);
            }
        } else {
            $resData = array('status' => false, 'message' => 'from number not exists.');
            return response()->json($resData);
        }
    }

//    public function msgSendAtTwilio(Request $request) {
//        $phoneNumber = '+17348384872';
//        $messageBody = 'kem 6e?';
//
//        $twilioMsgRes = TwilioLibrary::SendSMS($phoneNumber, $messageBody);
//        Log::info('in msgSendAtTwilio');
//        Log::info($twilioMsgRes);
//
//        $data = array('status' => true, 'message' => 'ok');
//        return response()->json($data);
//    }
}
