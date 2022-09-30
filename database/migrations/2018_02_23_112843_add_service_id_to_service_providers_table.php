<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddServiceIdToServiceProvidersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('service_providers', function (Blueprint $table) {
            $table->string('service_id')->default('')->after('provider_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('service_providers', function (Blueprint $table) {
            $table->dropColumn('service_id');
        });
    }

}
