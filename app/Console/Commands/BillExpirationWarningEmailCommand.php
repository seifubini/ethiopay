<?php

namespace App\Console\Commands;

use App\Mail\BillExpirationWarningMail;
use App\Models\UidLookup;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BillExpirationWarningEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expiration:bill-warning';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bill expiration Warning Email ';

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
        $expReminderDays = Config::get('ethiopay.DAYS_REMINDER_FOR_EXPIRE_BILL');
        $expBillDetails = UidLookup::where('cut_off_date', '=', Carbon::today('UTC')->addDays($expReminderDays)->format('Y-m-d'))
            ->get();
        foreach ($expBillDetails as $expBillDetail) {
            $users = User::all();
            foreach ($users as $user) {
                try {
                    $billExpirationWarningEmail = (new BillExpirationWarningMail($user));
                    Mail::to($user->email, $user->fullname)->send($billExpirationWarningEmail);

                } catch (\Exception $e) {
                    Log::info("Email Address Not Found.");
                }
            }
        }
    }
}
