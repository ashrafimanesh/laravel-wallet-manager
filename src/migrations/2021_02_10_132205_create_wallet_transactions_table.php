<?php

use Ashrafi\WalletManager\Facades\AccountModel;
use Ashrafi\WalletManager\Facades\TransactionTypeModel;
use Ashrafi\WalletManager\Facades\WalletModel;
use Ashrafi\WalletManager\Facades\WalletTransactionModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(WalletTransactionModel::getTable(), function (Blueprint $table) {
            $userTableName = (new \App\User())->getTable();

            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on($userTableName);

            $table->unsignedBigInteger('account_id');
            $table->foreign('account_id')->references('id')->on(AccountModel::getTable());

            $table->unsignedBigInteger('wallet_id');
            $table->foreign('wallet_id')->references('id')->on(WalletModel::getTable());

            $table->unsignedBigInteger('transaction_type_id');
            $table->foreign('transaction_type_id')->references('id')->on(TransactionTypeModel::getTable());

            $table->double('amount');

            $table->string("reference_type")->nullable();
            $table->unsignedBigInteger("reference_id")->nullable();

            $table->unsignedBigInteger('related_to')->nullable();
            $table->foreign('related_to')->on(WalletTransactionModel::getTable())->references('id');

            $table->string('status', 16)->default(\Ashrafi\WalletManager\Models\WalletTransaction::STATUS_PENDING);
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
        Schema::dropIfExists(WalletTransactionModel::getTable());
    }
}
