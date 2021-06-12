<?php

namespace Database\Seeders;

use Ashrafi\WalletManager\Facades\TransactionTypeModel;
use Ashrafi\WalletManager\Models\TransactionType;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TransactionTypeModel::updateOrCreate([
            'type'=>TransactionType::TYPE_CASH,
        ]);
        TransactionTypeModel::updateOrCreate([
            'type'=>TransactionType::TYPE_INIT,
        ]);
        TransactionTypeModel::updateOrCreate([
            'type'=>TransactionType::TYPE_TRANSFER,
        ]);
        TransactionTypeModel::updateOrCreate([
            'type'=>TransactionType::TYPE_BANK_RECEIPT,
        ]);
    }
}
