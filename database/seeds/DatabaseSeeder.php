<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $this->call([
            CountriesTableSeeder::class,
            StatesTableSeeder::class,
            CitiesTableSeeder::class,
            SettingsTableFirstSeeder::class,            
            ServiceTypesFirstTableSeeder::class,
            SecviceProvidersFirstSeeder::class,
            AdminsTableSeeder::class,
            AdminUserTableSeeder::class
        ]);
    }

}
