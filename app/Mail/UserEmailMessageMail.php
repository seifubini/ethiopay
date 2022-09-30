<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\SmsEmailMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserEmailMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user;
    public $emailMessage;

    public function __construct(User $user,SmsEmailMessage $emailMessage)
    {
        $this->user = $user;
        $this->emailMessage = $emailMessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(config('ethiopay.EMAIL.SUBJECT_PRE_TEXT') . $this->emailMessage->title)
            ->view('emails.admin.userEmailMessage');
    }
}
