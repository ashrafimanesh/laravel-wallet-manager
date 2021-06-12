<?php

use Ashrafi\WalletManager\Facades\TransactionTypes\CashTransactionModel;
use Ashrafi\WalletManager\Facades\WalletModel;
use Ashrafi\WalletManager\Models\TransactionTypes\CashTransaction;
use Ashrafi\WalletManager\Models\WalletTransaction;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(CashTransactionModel::getTable(), function (Blueprint $table) {
            $userTableName = app(\Ashrafi\WalletManager\Contracts\iUser::class)->getTable();

            $table->id();
            $table->unsignedBigInteger('wallet_id');
            $table->foreign('wallet_id')->references('id')->on(WalletModel::getTable());

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on($userTableName);

            $table->unsignedBigInteger('creator_id')->nullable();
            $table->foreign('creator_id')->references('id')->on($userTableName);

            $table->unsignedBigInteger('changed_by')->nullable();
            $table->foreign('changed_by')->references('id')->on($userTableName);

            $table->double('amount');
            $table->string('status',16)->default(WalletTransaction::STATUS_PENDING);

            $table->text('payload')->nullable();


            $table->timestamp('pay_date')->nullable();
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
        Schema::dropIfExists(CashTransactionModel::getTable());
    }
}
