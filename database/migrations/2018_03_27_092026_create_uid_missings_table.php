<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUidMissingsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('uid_missings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('service_type_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->string('uid')->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('uid_missings');
    }

}
