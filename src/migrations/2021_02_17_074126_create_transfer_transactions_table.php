<?php

use Ashrafi\WalletManager\Facades\TransactionTypes\TransferTransactionModel;
use Ashrafi\WalletManager\Facades\WalletModel;
use Ashrafi\WalletManager\Models\TransactionTypes\TransferTransaction;
use Ashrafi\WalletManager\Models\WalletTransaction;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(TransferTransactionModel::getTable(), function (Blueprint $table) {
            $user = app(\Ashrafi\WalletManager\Contracts\iUser::class);
            $table->bigIncrements('id');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->on($user->getTable())->references('id');

            $table->unsignedBigInteger('wallet_id');
            $table->foreign('wallet_id')->on(WalletModel::getTable())->references('id');

            $table->unsignedBigInteger('second_wallet_id');
            $table->foreign('second_wallet_id')->on(WalletModel::getTable())->references('id');

            $table->double('amount');
            $table->double('second_amount');

            $table->string('status', 16)->default(WalletTransaction::STATUS_PENDING);

            $table->text('payload')->nullable();

            $table->unsignedBigInteger('changed_by')->nullable();
            $table->foreign('changed_by')->references('id')->on($user->getTable());

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
        Schema::dropIfExists(TransferTransactionModel::getTable());
    }
}
