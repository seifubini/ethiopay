<?php

use Illuminate\Database\Seeder;

class AdminUserTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('admins')->insert([
            'name' => "EthioPay",
            'email' => 'superadmin@ethiopay.com',
            'password' => bcrypt('123456'),
            'user_type' => 'superadmin',
            'is_active' => '1',
            'created_at'=>date('Y-m-d H:i-s'),
            'updated_at'=>date('Y-m-d H:i-s')
        ]);
    }

}
