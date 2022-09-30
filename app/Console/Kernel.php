<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel {

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\CreateDirectoryCommand::class,
        Commands\MakeRepositoryCommand::class,
        Commands\CreateStripeCustomerAccountCommand::class,
        Commands\SendUserMonthlyServicesPayedEmailCommand::class,
        Commands\CardExpireWarningEmailCommand::class,
        Commands\smsEmailMessageCommand::class,
        Commands\BillExpirationWarningEmailCommand::class,
            // Commands\TestExecutionCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule) {
        if (App::environment('staging')) {
            if (Schema::hasTable('jobs') && DB::table('jobs')->count() > 0) {
                $schedule->exec('/usr/bin/php7.0 artisan queue:work --queue=stripe,emails,smsEmailMessage,twilio,default --tries=5');
            }

            $schedule->exec('/usr/bin/php7.0 artisan transaction:monthlyServicePayedMail')->monthlyOn(1, '02:00')->timezone(config('ethiopay.TIMEZONE_STR'))->withoutOverlapping();
            $schedule->exec('/usr/bin/php7.0 artisan email:cardExpireWarningMail')->daily()->timezone(config('ethiopay.TIMEZONE_STR'))->withoutOverlapping();                        
            $schedule->exec('/usr/bin/php7.0 artisan smsEmail:smsEmailMessage')->everyMinute()->withoutOverlapping();  
            $schedule->exec('/usr/bin/php7.0 artisan expiration:bill-warning')->daily()->timezone(config('ethiopay.TIMEZONE_STR'))->withoutOverlapping();
        
        } else {
            if (Schema::hasTable('jobs') && DB::table('jobs')->count() > 0) {
                $schedule->command('queue:work --queue=stripe,emails,smsEmailMessage,twilio,default --tries=5')->cron('* * * * * *')->withoutOverlapping();
            }
            $schedule->command('transaction:monthlyServicePayedMail')->monthlyOn(1, '02:00')->timezone(config('ethiopay.TIMEZONE_STR'))->withoutOverlapping();
            $schedule->command('email:cardExpireWarningMail')->daily()->timezone(config('ethiopay.TIMEZONE_STR'))->withoutOverlapping();            
            $schedule->command('smsEmail:smsEmailMessage')->everyMinute()->withoutOverlapping();         
            $schedule->command('expiration:bill-warning')->daily()->timezone(config('ethiopay.TIMEZONE_STR'))->withoutOverlapping();
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands() {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

}
