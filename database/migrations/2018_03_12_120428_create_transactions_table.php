<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->default(0);
            $table->integer('service_provider_id')->default(0);
            $table->integer('payment_method_id')->default(0);
            $table->string('customer_service_number')->default('');
            
            $table->double('customer_pay_amount')->default(0);
            $table->double('commision_in_percentage')->default(0);
            $table->double('commision_amount')->default(0);
            $table->double('total_pay_amount')->default(0);
            
            $table->string('stripe_transaction_id')->default('');
            $table->mediumText('stripe_transaction_response')->default('');
            $table->string('paypal_transaction_id')->default('');
            $table->mediumText('paypal_transaction_response')->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
