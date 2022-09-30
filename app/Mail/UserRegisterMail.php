<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;

class UserRegisterMail extends Mailable {

    use Queueable,
        SerializesModels;

    /**
     * The user instance.
     *
     * @var User
     */
    public $user;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user) {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->subject(config('ethiopay.EMAIL.SUBJECT_PRE_TEXT') . 'Registration')
                ->view('emails.register');
    }

}
