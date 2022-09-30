<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSomeFieldsToUsersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('users', function (Blueprint $table) {
            $table->string('ethiopia_country_code', 50)->default('')->comment('country code like: IN')->after('phone_number');
            $table->string('ethiopia_phone_code', 50)->default('')->comment('phone code like: +91')->after('ethiopia_country_code');
            $table->string('ethiopia_phone_number', 50)->default('')->comment('phone number like: 9998885557')->after('ethiopia_phone_code');

            $table->enum('is_email_verified', ['0', '1'])->default('0')->after('ethiopia_phone_number');
            $table->enum('is_phone_number_verified', ['0', '1'])->default('0')->after('is_email_verified');
            $table->enum('is_ethiopia_phone_number_verified', ['0', '1'])->default('0')->after('is_phone_number_verified');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'ethiopia_country_code',
                'ethiopia_phone_code',
                'ethiopia_phone_number',
                'is_email_verified',
                'is_phone_number_verified',
                'is_ethiopia_phone_number_verified'
            ]);
        });
    }

}
