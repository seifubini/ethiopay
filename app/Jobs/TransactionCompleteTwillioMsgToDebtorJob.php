<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;
use App\library\TwilioLibrary;

class TransactionCompleteTwillioMsgToDebtorJob implements ShouldQueue {

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    /**
     * The transaction instance.
     *
     * @var Transaction
     */
    public $transaction;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Transaction $transaction) {
        $this->transaction = $transaction;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        $debtorPhoneNumber = $this->transaction->debtor_phone_code . $this->transaction->debtor_phone_number;
        $messageBody = "Hello {$this->transaction->debtor_firstname} {$this->transaction->debtor_lastname},"
                . " Your Transaction has been completed."
                . " UID (Customer Service Number): {$this->transaction->customer_service_number},"
                . " Transaction ID: {$this->transaction->random_transaction_id},"
                . " Service Type: {$this->transaction->serviceProviderData->serviceTypeData->service_name},"
                . " Service Provider: {$this->transaction->serviceProviderData->provider_name},"
                . " Service Provider ID: {$this->transaction->serviceProviderData->service_id}";
        TwilioLibrary::SendSMS($debtorPhoneNumber, $messageBody);
    }

}
