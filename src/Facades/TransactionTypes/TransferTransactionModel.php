<?php


namespace Ashrafi\WalletManager\Facades\TransactionTypes;


use Ashrafi\WalletManager\Facades\Facade;
use Ashrafi\WalletManager\Models\TransactionTypes\TransferTransaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class TransferTransactionModel
 * @package Ashrafi\WalletManager\Facades\TransactionTypes
 * @method static Collection get()
 * @method static Builder|CashTransactionModel query()
 * @method static mixed find(mixed $id)
 * @method static TransferTransaction|null create(array $attributes)
 * @method static string getTable()
 * @method static TransferTransaction|null createRecord(array|null $data)
 */
class TransferTransactionModel extends Facade
{
    protected static function getFacadeAccessor()
    {
        return TransferTransaction::class;
    }
}
