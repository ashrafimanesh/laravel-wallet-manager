<?php


namespace Ashrafi\WalletManager\Facades;


use App\User;
use Ashrafi\WalletManager\Models\Account;
use Ashrafi\WalletManager\Models\TransactionType;
use Ashrafi\WalletManager\Models\Wallet;
use Ashrafi\WalletManager\Models\WalletTransaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class WalletTransactionModel
 * @package Ashrafi\WalletManager\Facades
 * @method static Collection get()
 * @method static Builder|WalletTransactionModel query()
 * @method static mixed find(mixed $id)
 * @method static WalletTransaction|null create(array $attributes)
 * @method static string getTable()
 * @method static Builder|WalletTransactionModel whereReference($reference)
 * @method static Builder|WalletTransactionModel whereReferenceType(string $referenceType)
 * @method static Builder|WalletTransactionModel whereReferenceId($referenceId)
 * @method static Builder|WalletTransactionModel approved()
 * @method static Builder|WalletTransactionModel pending()
 * @property User|null $user
 * @property Wallet|null $wallet
 * @property Account|null $account
 * @property TransactionType|null $transactionType
 * @property double $amount
 * @property string $reference_type
 * @property mixed $reference_id
 * @property mixed $transaction_type_id
 * @property mixed related_to
 * @property WalletTransaction|null related
 */
class WalletTransactionModel extends Facade
{

    protected static function getFacadeAccessor()
    {
        return WalletTransaction::class;
    }
}
