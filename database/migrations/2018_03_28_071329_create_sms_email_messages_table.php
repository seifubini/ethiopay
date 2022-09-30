<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsEmailMessagesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('sms_email_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('message_type', ['email', 'sms']);
            $table->enum('sent_type', ['now', 'schedule']);
            $table->enum('sent_status', ['0', '1'])->comment('0: pending, 1: completed');
            $table->dateTime('sent_datetime');
            $table->enum('users_selection_type', ['all', 'selected']);
            $table->text('users_id')->default('')->comment('selected users id');
            $table->mediumText('debtors_phone')->default('')->comment('selected debtors phone');
            $table->string('title')->default('');
            $table->text('description')->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('sms_email_messages');
    }

}