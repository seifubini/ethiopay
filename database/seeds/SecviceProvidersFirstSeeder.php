<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SecviceProvidersFirstSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::insert("INSERT INTO `service_providers` (`id`, `service_type_id`, `provider_name`, `service_id`, `created_at`, `updated_at`) VALUES
            (1, 1, 'Utility Bills - 1', '1001001', CONVERT_TZ(NOW(),@@session.time_zone,'+00:00'), CONVERT_TZ(NOW(),@@session.time_zone,'+00:00')),
            (2, 1, 'Utility Bills - 2', '1001002', CONVERT_TZ(NOW(),@@session.time_zone,'+00:00'), CONVERT_TZ(NOW(),@@session.time_zone,'+00:00')),
            (3, 1, 'Utility Bills - 3', '1001003', CONVERT_TZ(NOW(),@@session.time_zone,'+00:00'), CONVERT_TZ(NOW(),@@session.time_zone,'+00:00')),
            
            (4, 2, 'Health Insurance - 1', '2001001', CONVERT_TZ(NOW(),@@session.time_zone,'+00:00'), CONVERT_TZ(NOW(),@@session.time_zone,'+00:00')),
            (5, 2, 'Health Insurance - 2', '2001002', CONVERT_TZ(NOW(),@@session.time_zone,'+00:00'), CONVERT_TZ(NOW(),@@session.time_zone,'+00:00')),
            (6, 2, 'Health Insurance - 3', '2001003', CONVERT_TZ(NOW(),@@session.time_zone,'+00:00'), CONVERT_TZ(NOW(),@@session.time_zone,'+00:00')),
            
            (7, 3, 'School Fees - 1', '3001001', CONVERT_TZ(NOW(),@@session.time_zone,'+00:00'), CONVERT_TZ(NOW(),@@session.time_zone,'+00:00')),
            (8, 3, 'School Fees - 2', '3001002', CONVERT_TZ(NOW(),@@session.time_zone,'+00:00'), CONVERT_TZ(NOW(),@@session.time_zone,'+00:00')),
            (9, 3, 'School Fees - 3', '3001003', CONVERT_TZ(NOW(),@@session.time_zone,'+00:00'), CONVERT_TZ(NOW(),@@session.time_zone,'+00:00')),
            
            (10, 4, 'Pap Edir - 1', '4001001', CONVERT_TZ(NOW(),@@session.time_zone,'+00:00'), CONVERT_TZ(NOW(),@@session.time_zone,'+00:00')),
            (11, 4, 'Pap Edir - 2', '4001002', CONVERT_TZ(NOW(),@@session.time_zone,'+00:00'), CONVERT_TZ(NOW(),@@session.time_zone,'+00:00')),
            (12, 4, 'Pap Edir - 3', '4001003', CONVERT_TZ(NOW(),@@session.time_zone,'+00:00'), CONVERT_TZ(NOW(),@@session.time_zone,'+00:00')),
            
            (13, 5, 'Electricity Bills - 1', '5001001', CONVERT_TZ(NOW(),@@session.time_zone,'+00:00'), CONVERT_TZ(NOW(),@@session.time_zone,'+00:00')),
            (14, 5, 'Electricity Bills - 2', '5001002', CONVERT_TZ(NOW(),@@session.time_zone,'+00:00'), CONVERT_TZ(NOW(),@@session.time_zone,'+00:00')),
            (15, 5, 'Electricity Bills - 3', '5001003', CONVERT_TZ(NOW(),@@session.time_zone,'+00:00'), CONVERT_TZ(NOW(),@@session.time_zone,'+00:00'))
        ;");
    }

}
