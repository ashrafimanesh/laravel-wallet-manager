<?php


namespace Ashrafi\WalletManager\Facades;


use Ashrafi\WalletManager\Models\Currency;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class CurrencyModel
 * @package Ashrafi\WalletManager\Facades
 * @method static Collection get()
 * @method static Builder|CurrencyModel query()
 * @method static Builder|CurrencyModel whereSymbol($symbol)
 * @method static mixed find(mixed $id)
 * @method static Currency|null create(array $attributes)
 * @method static string getTable()
 * @method static string defaultSymbol()
 * @method static Builder|CurrencyModel active()
 */
class CurrencyModel extends Facade
{

    protected static function getFacadeAccessor()
    {
        return Currency::class;
    }

}
