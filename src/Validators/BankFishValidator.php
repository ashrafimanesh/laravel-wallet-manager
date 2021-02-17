<?php


namespace Ashrafi\WalletManager\Validators;


use Ashrafi\WalletManager\Exceptions\BankFishValidatorException;

class BankFishValidator implements iValidator
{

    /**
     * @param null $data
     * @throws BankFishValidatorException
     */
    public function validate($data = null)
    {
        if(!isset($data['bank_name'])){
            throw new BankFishValidatorException('Bank name can\t be null in bank_receipt transaction');
        }
        if(!isset($data['user_id'])){
            throw new BankFishValidatorException('User id can\t be null in bank_receipt transaction');
        }
        if(!isset($data['wallet_id'])){
            throw new BankFishValidatorException('Wallet id can\t be null in bank_receipt transaction');
        }
    }
}
