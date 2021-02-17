<?php


namespace Ashrafi\WalletManager\Facades;


use Ashrafi\WalletManager\Models\TransactionType;
use Ashrafi\WalletManager\Validators\iValidator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class TransactionTypeModel
 * @package Ashrafi\WalletManager\Facades
 * @method static Collection get()
 * @method static Builder|TransactionTypeModel query()
 * @method static Builder|TransactionTypeModel whereType($type)
 * @method static Builder|TransactionTypeModel active()
 * @method static mixed find(mixed $id)
 * @method static TransactionType|null create(array $attributes)
 * @method static string getTable()
 * @method static iValidator|null staticGetValidator(string $type)
 */
class TransactionTypeModel extends Facade
{

    protected static function getFacadeAccessor()
    {
        return TransactionType::class;
    }

}
