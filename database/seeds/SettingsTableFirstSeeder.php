<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableFirstSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::insert("INSERT INTO `settings` (`id`, `key`, `value`, `description`, `created_at`, `updated_at`) VALUES
            (1, 'Payment_Fee_In_Percentage', '10', '10% commission as a Payment Fee', CONVERT_TZ(NOW(),@@session.time_zone,'+00:00'), CONVERT_TZ(NOW(),@@session.time_zone,'+00:00'));
        ");
    }

}
