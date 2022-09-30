<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\ServiceProvider;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ServicePayedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user;
    public $transaction;
    public $serviceProvider;
    

    public function __construct(User $user,Transaction $transaction,ServiceProvider $serviceProvider)
    {
        //
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
        return $this->subject(config('ethiopay.EMAIL.SUBJECT_PRE_TEXT') . 'Service Payed')
                ->view('emails.servicePayed');
    }
}
