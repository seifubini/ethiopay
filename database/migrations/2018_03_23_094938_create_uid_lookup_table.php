<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUidLookupTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('uid_lookups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('service_type_id')->default(0);
            $table->string('service_id')->default('');
            $table->string('uid')->default('');
            $table->string('debtor_firstname')->default('');
            $table->string('debtor_lastname')->default('');
            $table->string('debtor_city')->default('');
            $table->string('debtor_phone')->default('');
            $table->double('amount')->default(0);
            $table->date('cut_off_date')->nullable()->default(null);
            $table->date('billing_period_start')->nullable()->default(null);
            $table->date('billing_period_end')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('uid_lookups');
    }

}
