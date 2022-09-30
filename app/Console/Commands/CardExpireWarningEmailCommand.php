<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\PaymentMethod;
use Illuminate\Console\Command;
use App\Mail\UserCardExpireMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class CardExpireWarningEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:cardExpireWarningMail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Card Expire Warning Email';

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
        $expReminderDays = Config::get('ethiopay.DAYS_REMINDER_FOR_EXPIRE_CARD');
        $expCardDetails = PaymentMethod::select('*',
            DB::raw('DATE_FORMAT( LAST_DAY(CONCAT(card_expiry_year, "-" ,card_expiry_month, "-01")), "%Y-%m-%d" )  as date')
        )
        ->having('date', '=', Carbon::today('UTC')->addDays($expReminderDays)->format('Y-m-d'))
        ->get()->toArray();

        foreach($expCardDetails as $expCardDetail) {
            $user = User::find($expCardDetail['user_id']);
            
            $emailMessage = (new UserCardExpireMail($user, $expCardDetail));
            Mail::to($user->email, $user->fullname)->send($emailMessage);
        }
    }
}
