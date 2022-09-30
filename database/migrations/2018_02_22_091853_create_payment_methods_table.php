<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentMethodsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->default(0);
            $table->enum('is_default_method', ['0', '1'])->default('0');
            $table->enum('method_type', ['card', 'paypal'])->default('card');

            $table->string('card_type', 50)->default('');
            $table->string('name_on_card')->default('');
            $table->string('card_number')->default('');
            $table->string('card_expiry_month', 20)->default('');
            $table->string('card_expiry_year', 20)->default('');
            $table->string('stripe_card_id')->default('');
            $table->string('stripe_card_fingerprint')->default('');

            $table->string('paypal_email')->default('');
            $table->string('paypal_firstname')->default('');
            $table->string('paypal_lastname')->default('');
            $table->string('paypal_refresh_token')->default('');
            $table->string('paypal_client_metadata_id')->default('');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('payment_methods');
    }

}
