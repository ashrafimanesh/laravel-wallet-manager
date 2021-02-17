<?php


namespace Ashrafi\WalletManager\Facades;


use Ashrafi\WalletManager\Models\Currency;
use Ashrafi\WalletManager\Models\Wallet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * @method static Collection get()
 * @method static Builder|WalletModel query()
 * @method static mixed find(mixed $id)
 * @method static Builder|WalletModel userCurrency($userId, $currencyId)
 * @method static Builder|WalletModel whereUnique(array $attributes)
 * @method static Wallet|null create(array $attributes)
 * @method static string getTable()
 * @method static Builder|WalletModel active()
 * @method static Builder|WalletModel whereAccount($accountId)
 * @method static Builder|WalletModel whereUser($userId)
 * @method static Builder|WalletModel whereCurrency($currencyId)
 * @method static string defaultStatus()
 * @method double walletBalance($symbol = null)
 * @property mixed balance
 * @property mixed user_id
 * @property mixed account_id
 * @property mixed currency_id
 * @property mixed config
 * @property Currency|mixed currency
 * @property mixed freeze_balance
 * @property mixed credit
 */
class WalletModel extends Facade
{

    protected static function getFacadeAccessor()
    {
       return Wallet::class;
    }
}
