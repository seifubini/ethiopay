<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\UidMissing;

class UidMissingServiceAvailabelMail extends Mailable {

    use Queueable,
        SerializesModels;

    /**
     * The user instance.
     *
     * @var User
     */
    public $user;
    
    /**
     * The UidMissing instance.
     *
     * @var UidMissing
     */
    public $uidMissing;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(UidMissing $uidMissing) {
        $this->uidMissing = $uidMissing;
        $this->user = $uidMissing->userData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->subject(config('ethiopay.EMAIL.SUBJECT_PRE_TEXT') . 'New Service Availabe')
                ->view('emails.admin.uid-missing-service-available');
    }

}
