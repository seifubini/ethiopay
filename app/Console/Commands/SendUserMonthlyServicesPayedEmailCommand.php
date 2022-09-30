<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserMonthlyServicesPayedMail;

class SendUserMonthlyServicesPayedEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:monthlyServicePayedMail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Monthly Payed Services Email';

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
        //
        $firstDayofPreviousMonth = Carbon::now('UTC')->subMonth()->startOfMonth();
        $lastDayofPreviousMonth = Carbon::now('UTC')->subMonth()->endOfMonth();

        $usersId = Transaction::groupBy('user_id')
        ->whereBetween('created_at', [$firstDayofPreviousMonth, $lastDayofPreviousMonth])        
        ->get()->toArray();
        foreach ($usersId as $key => $userId) {
            $transactions = Transaction::select('service_types.service_name', 'transactions.user_id',
                DB::raw("COALESCE(FORMAT(SUM(transactions.total_pay_amount), 2), 0) AS transactionsAmountSum"),
                DB::raw("COUNT(transactions.id) as totalTransaction"))
                ->leftJoin('service_providers', 'service_providers.id', '=', 'transactions.service_provider_id')
                ->leftJoin('service_types', 'service_types.id', '=', 'service_providers.service_type_id')
                ->where('transactions.user_id', $userId['user_id'])
                ->whereBetween('transactions.created_at', [$firstDayofPreviousMonth, $lastDayofPreviousMonth])
                ->groupBy('service_types.id')
                ->get();
            $user = User::find($userId['user_id']);
            $emailQueueMessage = (new UserMonthlyServicesPayedMail($user, $transactions));
            Mail::to($user->email, $user->fullname)->send($emailQueueMessage);
        }
    }
}
