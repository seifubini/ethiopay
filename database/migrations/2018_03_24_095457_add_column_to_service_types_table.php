<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToServiceTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('service_types', function (Blueprint $table) {
            $table->double('payment_fee_in_percentage')->default(0)->after('service_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('service_types', function (Blueprint $table) {
            $table->dropColumn('payment_fee_in_percentage');
        });
    }
}
