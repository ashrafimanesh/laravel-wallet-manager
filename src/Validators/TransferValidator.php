<?php


namespace Ashrafi\WalletManager\Validators;


use Ashrafi\WalletManager\Exceptions\TransactionValidatorException;

class TransferValidator implements iValidator
{

    /**
     * @inheritDoc
     */
    public function validate($data = null)
    {
        if(!isset($data['user_id'])){
            throw new TransactionValidatorException('User id can\t be null in transfer transaction');
        }
        if(!isset($data['wallet_id'])){
            throw new TransactionValidatorException('Wallet id can\t be null in transfer transaction');
        }
        if(!isset($data['second_wallet_id'])){
            throw new TransactionValidatorException('Second wallet id can\t be null in transfer transaction');
        }
        if(!isset($data['amount'])){
            throw new TransactionValidatorException('Amount can\t be null in transfer transaction');
        }
        if(!isset($data['second_amount'])){
            throw new TransactionValidatorException('Second amount can\t be null in transfer transaction');
        }
    }
}
