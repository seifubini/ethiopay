<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateServiceProviderName extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('service_providers', function (Blueprint $table) {
            $table->renameColumn('name', 'provider_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('service_providers', function (Blueprint $table) {
            $table->renameColumn('provider_name', 'name');
        });
    }

}
