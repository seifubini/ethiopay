<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\UserRegisterMail;
use Mail;
use App\Models\User;

class TestController extends Controller {

    public function emailTestSchedule(Request $request, $id) {
        $user = User::find($id);
        
        $emailQueueMessage = (new UserRegisterMail($user))->onQueue('emails');
        $mailRes = Mail::to($user->email, $user->fullname)->queue($emailQueueMessage);        
        
//        $emailQueueMessage = (new UserRegisterMail($user));
//        $mailRes = Mail::to($user->email, $user->fullname)->send($emailQueueMessage);
        
        $data = array(
            'mailRes' => $mailRes
        );
        return response()->json($data);
    }
    
    public function emailTest(Request $request, $id) {
        $user = User::find($id);     
        
        $emailQueueMessage = (new UserRegisterMail($user));
        $mailRes = Mail::to($user->email, $user->fullname)->send($emailQueueMessage);
        
        $data = array(
            'mailRes' => $mailRes
        );
        return response()->json($data);
    }

}
