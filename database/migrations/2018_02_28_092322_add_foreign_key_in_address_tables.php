<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyInAddressTables extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
//        Schema::table('addresses', function (Blueprint $table) {
//            $table->integer('user_id')->unsigned()->change();
//            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
//        Schema::table('addresses', function (Blueprint $table) {
//            $table->dropForeign('addresses_user_id_foreign');
//        });
    }

}
