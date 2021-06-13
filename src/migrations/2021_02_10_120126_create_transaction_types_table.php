<?php

use Ashrafi\WalletManager\Facades\TransactionTypeModel;
use Ashrafi\WalletManager\Models\TransactionType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(TransactionTypeModel::getTable(), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type',16)->unique();
            $table->tinyInteger('status')->default(TransactionType::STATUS_ACTIVE);
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
        Schema::dropIfExists(TransactionTypeModel::getTable());
    }
}
