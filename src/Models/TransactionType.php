<?php


namespace Ashrafi\WalletManager\Models;


use Ashrafi\WalletManager\Contracts\iRelatedTransactionTypeCreateRecord;
use Ashrafi\WalletManager\Contracts\iRelatedTransactionTypeValidate;
use Ashrafi\WalletManager\Factories\TransactionTypeFactory;
use Ashrafi\WalletManager\Models\TransactionTypes\BankReceiptTransaction;
use Ashrafi\WalletManager\Models\TransactionTypes\CashTransaction;
use Ashrafi\WalletManager\Models\TransactionTypes\TransferTransaction;
use Ashrafi\WalletManager\Validators\iValidator;

/**
 * @property mixed type
 */
class TransactionType extends Model
{
    const TYPE_CASH = 'cash';
    const TYPE_CREDIT = 'credit';
    const TYPE_BANK_RECEIPT = 'bank_receipt';
    const TYPE_CRYPTO = 'crypto';
    const TYPE_POS = 'pos';
    const TYPE_INIT = 'init';
    const TYPE_TRANSFER = 'transfer';

    public function getValidator(){
        return static::staticGetValidator($this->type);
    }

    public function scopeActive($builder){
        return $builder->where('status', static::STATUS_ACTIVE);
    }

    public function scopeWhereType($builder, $type){
        return $builder->where('type', $type);
    }

    public function getTable()
    {
        return config('wallet_manager.database.prefix')."transaction_types";
    }


    /**
     * @param $type
     * @return iValidator|null
     */
    public static function staticGetValidator($type){
        /** @var TransactionTypeFactory $factory */
        $factory = app(TransactionTypeFactory::class);
        $handler = $factory->make($type);
        if(!$handler || !($handler instanceof iRelatedTransactionTypeValidate)){
            return null;
        }
        return $handler;
    }

    /**
     * @param array|null $data
     * @return BankReceiptTransaction|CashTransaction|TransferTransaction|null
     */
    public function createReference(array $data = null)
    {
        /** @var TransactionTypeFactory $factory */
        $factory = app(TransactionTypeFactory::class);
        $handler = $factory->make($this->type);
        if(!$handler || !($handler instanceof iRelatedTransactionTypeCreateRecord)){
            return null;
        }
        return $handler->createRecord($data);
    }

}
