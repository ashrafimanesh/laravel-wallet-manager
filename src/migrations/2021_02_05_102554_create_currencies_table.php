<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(\Ashrafi\WalletManager\Facades\CurrencyModel::getTable(), function (Blueprint $table) {
            $table->id();
            $table->string('symbol', 8)->unique();
            $table->tinyInteger('status')->default(\Ashrafi\WalletManager\Models\Currency::STATUS_ACTIVE);
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
        Schema::dropIfExists(\Ashrafi\WalletManager\Facades\CurrencyModel::getTable());
    }
}
