<?php


namespace Ashrafi\WalletManager\Facades;


use Ashrafi\WalletManager\Models\Account;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class AccountModel
 *
 * @method static Builder|AccountModel whereUnique(array $attributes)
 * @method static Collection get()
 * @method static Builder|AccountModel query()
 * @method static mixed find(mixed $id)
 * @method static Account|null create(array $attributes)
 * @method static string getTable()
 * @method static Builder|AccountModel active()
 * @method static Builder|AccountModel whereUser($user)
 * @method static Builder|AccountModel whereType($type)
 * @method static string defaultType()
 * @method static int defaultStatus()
 *
 * @property mixed user_id
 * @property array|mixed wallets
 */
class AccountModel extends Facade
{

    protected static function getFacadeAccessor()
    {
        return Account::class;
    }

}
