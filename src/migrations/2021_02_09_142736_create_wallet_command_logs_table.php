<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletCommandLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create((new \Ashrafi\WalletManager\Models\CommandLogs())->getTable(), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('model');

            $table->string('entity_type');
            $table->longText('entity_data');

            $table->unsignedBigInteger('owner_id')->nullable();

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
        Schema::dropIfExists((new \Ashrafi\WalletManager\Models\CommandLogs())->getTable());
    }
}
