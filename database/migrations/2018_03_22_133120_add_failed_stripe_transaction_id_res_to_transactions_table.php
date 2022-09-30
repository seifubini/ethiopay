<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Transaction;

class AddFailedStripeTransactionIdResToTransactionsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('transactions', function (Blueprint $table) {
            $table->enum('transaction_status', ['failed', 'pending', 'succeeded'])->after('total_pay_amount');
        });
        Transaction::query()->update(['transaction_status' => 'succeeded']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'transaction_status'
            ]);
        });
    }

}
