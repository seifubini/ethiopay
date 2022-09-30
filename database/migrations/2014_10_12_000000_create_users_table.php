<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstname')->default('');
            $table->string('lastname')->default('');
            $table->string('email', 191)->default('');
            $table->string('password')->default('');
            $table->rememberToken()->default('');
            $table->string('profile_picture')->default('');
            $table->string('country_code', 50)->default('')->comment('country code like: IN');
            $table->string('phone_code', 50)->default('')->comment('phone code like: +91');
            $table->string('phone_number', 50)->default('')->comment('phone number like: 9998885557');
            $table->string('federal_tax_id')->default('');
            $table->string('stripe_customer_id')->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('users');
    }

}
