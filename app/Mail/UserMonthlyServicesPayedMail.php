<?php

namespace App\Mail;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserMonthlyServicesPayedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user;
    public $transactions;

    public function __construct(User $user,$transactions)
    {
        // dd($user, $transactions);
        $this->user = $user;
        $this->transactions = $transactions;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(config('ethiopay.EMAIL.SUBJECT_PRE_TEXT') . 'Monthly Services Payed')
            ->view('emails.monthlyServicesPayed');
    }
}
