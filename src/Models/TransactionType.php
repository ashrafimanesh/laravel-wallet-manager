<?php


namespace Ashrafi\WalletManager\Models;


use Ashrafi\WalletManager\Facades\TransactionTypes\BankReceiptTransactionModel;
use Ashrafi\WalletManager\Facades\TransactionTypes\CashTransactionModel;
use Ashrafi\WalletManager\Facades\TransactionTypes\TransferTransactionModel;
use Ashrafi\WalletManager\Models\TransactionTypes\BankReceiptTransaction;
use Ashrafi\WalletManager\Models\TransactionTypes\CashTransaction;
use Ashrafi\WalletManager\Models\TransactionTypes\TransferTransaction;
use Ashrafi\WalletManager\Validators\BankFishValidator;
use Ashrafi\WalletManager\Validators\CashValidator;
use Ashrafi\WalletManager\Validators\iValidator;
use Ashrafi\WalletManager\Validators\TransferValidator;

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
        switch ($type){
            case self::TYPE_BANK_RECEIPT:
                return app(BankFishValidator::class);
            case self::TYPE_CASH:
                return app(CashValidator::class);
            case self::TYPE_TRANSFER:
                return app(TransferValidator::class);
        }
    }

    /**
     * @param array|null $data
     * @return BankReceiptTransaction|CashTransaction|TransferTransaction|null
     */
    public function createReference(array $data = null)
    {
        switch ($this->type){
            case self::TYPE_BANK_RECEIPT:
                return BankReceiptTransactionModel::createRecord($data);
            case self::TYPE_CASH:
                return CashTransactionModel::createRecord($data);
            case self::TYPE_TRANSFER:
                return TransferTransactionModel::createRecord($data);
        }
    }

}
