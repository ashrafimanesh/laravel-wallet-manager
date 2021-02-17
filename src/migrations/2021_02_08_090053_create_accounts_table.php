<?php

use Ashrafi\WalletManager\Facades\AccountModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(AccountModel::getTable(), function (Blueprint $table) {
            $userTableName = (new \App\User())->getTable();
            $table->id();

            $table->string('name', 128);
            $table->string('label', 128)->nullable();
            $table->string('type', 64)->default(AccountModel::defaultType());

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on($userTableName);

            $table->tinyInteger('status')->default(AccountModel::defaultStatus());

            $table->unsignedBigInteger('locked_by')->nullable();
            $table->foreign('locked_by')->references('id')->on($userTableName);

            $table->text('config')->nullable();

            $table->dateTime('locked_at')->nullable();
            $table->text('lock_description')->nullable();

            $table->unique(['name', 'user_id', 'type']);
            $table->index(['user_id', 'type']);

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
        Schema::dropIfExists(AccountModel::getTable());
    }
}
