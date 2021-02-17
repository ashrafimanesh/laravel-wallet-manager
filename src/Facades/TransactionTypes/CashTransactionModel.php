<?php


namespace Ashrafi\WalletManager\Facades\TransactionTypes;


use App\User;
use Ashrafi\WalletManager\Facades\Facade;
use Ashrafi\WalletManager\Models\TransactionTypes\CashTransaction;
use Ashrafi\WalletManager\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class CashTransactionModel
 * @package Ashrafi\WalletManager\Facades
 * @method static Collection get()
 * @method static Builder|CashTransactionModel query()
 * @method static mixed find(mixed $id)
 * @method static CashTransaction|null create(array $attributes)
 * @method static string getTable()
 * @method static CashTransaction|null createRecord(array|null $data)
 * @property User|null $user
 * @property User|null $creator
 * @property Wallet|null $wallet
 * @property double $amount
 * @property Carbon $pay_date
 */
class CashTransactionModel extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CashTransaction::class;
    }
}
