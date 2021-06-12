<?php


namespace Ashrafi\WalletManager\Facades\TransactionTypes;

use Ashrafi\WalletManager\Contracts\iUser as User;
use Ashrafi\WalletManager\Facades\Facade;
use Ashrafi\WalletManager\Models\TransactionTypes\BankReceiptTransaction;
use Ashrafi\WalletManager\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class BankFishTransactionModel
 * @package Ashrafi\WalletManager\Facades
 * @method static Collection get()
 * @method static Builder|BankReceiptTransactionModel query()
 * @method static mixed find(mixed $id)
 * @method static BankReceiptTransaction|null create(array $attributes)
 * @method static string getTable()
 * @method static BankReceiptTransaction|null createRecord(array $data = null)
 * @property User|null $user
 * @property User|null $creator
 * @property Wallet|null $wallet
 * @property double $amount
 * @property Carbon $pay_date
 * @property string $bank_name
 *
 */
class BankReceiptTransactionModel extends Facade
{

    protected static function getFacadeAccessor()
    {
        return BankReceiptTransaction::class;
    }
}
