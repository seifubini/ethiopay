<?php

namespace App\Jobs;

use App\Mail\UserEmailMessageMail;
use App\Models\SmsEmailMessage;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SmsEmailMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $smsEmailMessage;

    public function __construct(SmsEmailMessage $smsEmailMessage)
    {
        $this->smsEmailMessage = $smsEmailMessage;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $smsEmailMessage = $this->smsEmailMessage;
        if ($smsEmailMessage->message_type == 'sms' && $smsEmailMessage->sent_type == 'now') {
            $TWILIO_SID = env('TWILIO_SID');
            $TWILIO_TOKEN = env('TWILIO_TOKEN');
            $twilioClient = new \Twilio\Rest\Client($TWILIO_SID, $TWILIO_TOKEN);
            config(['ethiopay.twilio.client' => $twilioClient]);

            $users_phone = [];
            $debtor_phone = [];
            if ($smsEmailMessage->users_id != "") {
                $users_id = explode(",", $smsEmailMessage->users_id);
                foreach ($users_id as $user_id) {
                    $user = User::find($user_id);
                    $users_phone[] = $user->phone_code . '' . $user->phone_number;
                }
            }
            if ($smsEmailMessage->debtors_phone != "") {
                $debtor_phone = explode(",", $smsEmailMessage->debtors_phone);
            }
            $phone_number = array_merge($users_phone, $debtor_phone);
            foreach ($phone_number as $phone) {
                try {
                    $twilioClient = config("ethiopay.twilio.client");
                    $sms = $twilioClient->account->messages->create($phone, array('from' => env('TWILIO_PHONE_NO'), 'body' => $smsEmailMessage->title . "\n" . $smsEmailMessage->description));
                    // Log::info($twilioClient);
                } catch (\Exception $e) {
                    Log::info("Something went Wrong.");
                }
            }
        } else if ($smsEmailMessage->message_type == 'email' && $smsEmailMessage->sent_type == 'now') {
            $users_id = explode(",", $smsEmailMessage->users_id);

            foreach ($users_id as $user_id) {
                $user = User::find($user_id);
                try {
                    $emailMessage = (new UserEmailMessageMail($user, $smsEmailMessage));
                    Mail::to($user->email, $user->fullname)->send($emailMessage);
                } catch (\Exception $e) {
                    Log::info("Email Not Found.");
                }
            }
        }
    }
}
