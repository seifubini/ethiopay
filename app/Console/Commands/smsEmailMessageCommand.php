<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\SmsEmailMessage;
use Illuminate\Console\Command;
use App\Mail\UserEmailMessageMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class smsEmailMessageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smsEmail:smsEmailMessage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send sms and email to payours and debtors';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $today = Carbon::now('UTC')->toDateTimeString();
        $smsEmailMessages = SmsEmailMessage::where('sent_status', '0')->where('sent_type','schedule')->where('sent_datetime','<',$today)->get();

        foreach ($smsEmailMessages as $smsEmailMessage) {
            if($smsEmailMessage->message_type == 'email') {
                $users_id = explode(",", $smsEmailMessage->users_id);
                foreach($users_id as $user_id) {
                    $user = User::find($user_id);
                    try {
                        $emailMessage = (new UserEmailMessageMail($user, $smsEmailMessage));
                        Mail::to($user->email, $user->fullname)->send($emailMessage);

                        $smsEmailMessage->sent_status = '1';
                        $smsEmailMessage->save();
                    } catch (\Exception $e) {
                        Log::info("Email Not Found.");
                    }
                }
            }
    
            if($smsEmailMessage->message_type == 'sms') {
                $TWILIO_SID = env('TWILIO_SID');
                $TWILIO_TOKEN = env('TWILIO_TOKEN');
                $twilioClient = new \Twilio\Rest\Client($TWILIO_SID, $TWILIO_TOKEN);
                config(['ethiopay.twilio.client' => $twilioClient]);

                $users_phone = [];
                $debtor_phone = [];
                if ($smsEmailMessage->users_id != "") {
                    $users_id = explode(",", $smsEmailMessage->users_id);
                    foreach($users_id as $user_id) {
                        $user = User::find($user_id);
                        $users_phone[] = $user->phone_code . '' . $user->phone_number;
                    }
                }
                
                if($smsEmailMessage->debtors_phone != "")
                    $debtor_phone = explode(",", $smsEmailMessage->debtors_phone);
    
                $phone_number = array_merge($users_phone,$debtor_phone);
                try {
                    foreach ($phone_number as $phone) {
                        $twilioClient = config("ethiopay.twilio.client");
                        $sms = $twilioClient->account->messages->create($phone, array('from' => env('TWILIO_PHONE_NO'), 'body' => $smsEmailMessage->title . "\n" . $smsEmailMessage->description));
                    }

                    $smsEmailMessage->sent_status = '1';
                    $smsEmailMessage->save();
                } catch (\Exception $e) {
                    Log::info("Something went Wrong.");
                }
            }
        }
    }
}
