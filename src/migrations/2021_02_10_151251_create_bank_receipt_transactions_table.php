<?php

use Ashrafi\WalletManager\Facades\TransactionTypes\BankReceiptTransactionModel;
use Ashrafi\WalletManager\Facades\WalletModel;
use Ashrafi\WalletManager\Models\WalletTransaction;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankReceiptTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(BankReceiptTransactionModel::getTable(), function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->unsignedBigInteger('wallet_id');
            $table->foreign('wallet_id')->references('id')->on(WalletModel::getTable());

            $table->unsignedBigInteger('user_id');

            $table->unsignedBigInteger('creator_id')->nullable();

            $table->unsignedBigInteger('changed_by')->nullable();

            $table->double('amount');
            $table->timestamp('pay_date')->nullable();

            $table->string('bank_name');

            $table->string('serial')->nullable();
            $table->string('status',16)->default(WalletTransaction::STATUS_PENDING);

            $table->text('payload')->nullable();

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
        Schema::dropIfExists(BankReceiptTransactionModel::getTable());
    }
}
