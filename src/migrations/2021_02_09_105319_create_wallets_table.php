<?php

use Ashrafi\WalletManager\Facades\AccountModel;
use Ashrafi\WalletManager\Facades\CurrencyModel;
use Ashrafi\WalletManager\Facades\WalletModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(WalletModel::getTable(), function (Blueprint $table) {
            $userTableName = (new \App\User())->getTable();

            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on($userTableName);

            $table->unsignedBigInteger('currency_id');
            $table->foreign('currency_id')->references('id')->on(CurrencyModel::getTable());

            $table->double('balance')->default(0);
            $table->double('freeze_balance')->default(0);

            $table->unsignedBigInteger('account_id');
            $table->foreign('account_id')->references('id')->on(AccountModel::getTable());

            $table->unsignedBigInteger('creator_id')->nullable();
            $table->foreign('creator_id')->references('id')->on($userTableName);

            $table->tinyInteger('status')->default(WalletModel::defaultStatus());

            $table->unsignedBigInteger('locked_by')->nullable();
            $table->foreign('locked_by')->references('id')->on($userTableName);

            $table->text('config')->nullable();

            $table->dateTime('locked_at')->nullable();
            $table->text('lock_description')->nullable();

            $table->timestamps();
            $table->unique(['currency_id', 'account_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(WalletModel::getTable());
    }
}
