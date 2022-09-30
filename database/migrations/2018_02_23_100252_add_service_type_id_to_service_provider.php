<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddServiceTypeIdToServiceProvider extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('service_providers', function (Blueprint $table) {
            $table->integer('service_type_id')->default(0)->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('service_providers', function (Blueprint $table) {
            $table->dropColumn('service_type_id');
        });
    }

}
