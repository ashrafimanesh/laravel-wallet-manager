<?php

use Ashrafi\WalletManager\Facades\WalletTransactionLogModel;
use Ashrafi\WalletManager\Facades\WalletTransactionModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletTransactionLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(WalletTransactionLogModel::getTable(), function (Blueprint $table) {
            $userTableName = app(\Ashrafi\WalletManager\Contracts\iUser::class)->getTable();
            $table->bigIncrements('id');
            $table->unsignedBigInteger('wallet_transaction_id');
            $table->foreign('wallet_transaction_id')->on(WalletTransactionModel::getTable())->references('id');

            $table->string('field');
            $table->text('from_value')->nullable();
            $table->text('to_value')->nullable();

            $table->unsignedBigInteger('done_by');
            $table->foreign('done_by')->on($userTableName)->references('id');

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
        Schema::dropIfExists(WalletTransactionLogModel::getTable());
    }
}
