<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyInServiceProviderTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
//        Schema::table('service_providers', function (Blueprint $table) {
//            $table->integer('service_type_id')->unsigned()->change();
//            $table->foreign('service_type_id')->references('id')->on('service_types')->onDelete('cascade')->onUpdate('cascade');
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
//        Schema::table('service_providers', function (Blueprint $table) {
//            $table->dropForeign('service_providers_service_type_id_foreign');
//        });
    }

}
