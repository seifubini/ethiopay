<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceTypesFirstTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::insert("INSERT INTO `service_types` (`id`, `service_name`, `created_at`, `updated_at`) VALUES
            (1, 'Utility Bills', CONVERT_TZ(NOW(),@@session.time_zone,'+00:00'), CONVERT_TZ(NOW(),@@session.time_zone,'+00:00')),
            (2, 'Health Insurance', CONVERT_TZ(NOW(),@@session.time_zone,'+00:00'), CONVERT_TZ(NOW(),@@session.time_zone,'+00:00')),
            (3, 'School Fees', CONVERT_TZ(NOW(),@@session.time_zone,'+00:00'), CONVERT_TZ(NOW(),@@session.time_zone,'+00:00')),
            (4, 'Pap Edir', CONVERT_TZ(NOW(),@@session.time_zone,'+00:00'), CONVERT_TZ(NOW(),@@session.time_zone,'+00:00')),
            (5, 'Electricity Bills', CONVERT_TZ(NOW(),@@session.time_zone,'+00:00'), CONVERT_TZ(NOW(),@@session.time_zone,'+00:00'));
        ");
    }

}
