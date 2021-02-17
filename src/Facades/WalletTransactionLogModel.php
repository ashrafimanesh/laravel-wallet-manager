<?php


namespace Ashrafi\WalletManager\Facades;


use Ashrafi\WalletManager\Models\WalletTransactionLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class WalletTransactionLogModel
 * @package Ashrafi\WalletManager\Facades
 * @method static Collection get()
 * @method static Builder|WalletTransactionLogModel query()
 * @method static mixed find(mixed $id)
 * @method static WalletTransactionLog|null create(array $attributes)
 * @method static string getTable()
 */
class WalletTransactionLogModel extends Facade
{

    protected static function getFacadeAccessor()
    {
        return WalletTransactionLog::class;
    }

}
