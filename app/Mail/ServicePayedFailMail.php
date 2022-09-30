<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\ServiceProvider;
use Illuminate\Queue\SerializesModels;

class ServicePayedFailMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $transaction;
    public $serviceProvider;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Transaction $transaction, ServiceProvider $serviceProvider)
    {
        $this->user = $user;
        $this->transaction = $transaction;
        $this->serviceProvider = $serviceProvider;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(config('ethiopay.EMAIL.SUBJECT_PRE_TEXT') . 'Service Payed Fail')
            ->view('emails.servicePayedFail');
    }
}
