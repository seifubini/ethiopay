<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUidlookupRelatedFieldsToTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->integer('uid_lookup_id')->default(0)->after('user_id');
            $table->integer('service_type_id')->default(0)->after('uid_lookup_id');
            $table->string('debtor_firstname')->default('')->after('customer_service_number');
            $table->string('debtor_lastname')->default('')->after('debtor_firstname');
            $table->string('debtor_city')->default('')->after('debtor_lastname');
            $table->string('debtor_phone_code', 50)->default('')->comment('code like: +91')->after('debtor_city');
            $table->string('debtor_phone_number', 50)->default('')->comment('number like: 9998885557')->after('debtor_phone_code');
            $table->date('cut_off_date')->nullable()->default(null)->after('debtor_phone_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'uid_lookup_id',
                'service_type_id',
                'debtor_firstname',
                'debtor_lastname',
                'debtor_city',
                'debtor_phone_code',
                'debtor_phone_number',
                'cut_off_date'
            ]);
        });
    }
}
