<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminsTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('admins')->insert([
            'name' => 'Milan Katariya',
            'email' => 'milan@logisticinfotech.com',
            'password' => bcrypt('123456'),
            'is_active' => '1',
            'user_type' => 'superadmin',
            'created_at'=>date('Y-m-d H:i-s'),
            'updated_at'=>date('Y-m-d H:i-s')
        ]);

//        DB::insert("INSERT INTO `admins` (`id`, `name`, `email`, `password`, `remember_token`, `user_type`, `is_active`, `created_at`, `updated_at`) VALUES
//            (2, 'superadmin', 'milan@logisticinfotech.com', '$2y$10\$xahz4IJaef/vF4XlH1fli.uIsIWkkPIud4ijhQyNCszCaBlBT3tvG', 'Z1WARNHInymVJkK3S82uEHR7abQ1jeTQhAWWRiIYMS91FWqfWcIU8CIjROT5', 'superadmin', '1', NULL, '2018-02-27 03:11:22');
//        ");
    }

}
