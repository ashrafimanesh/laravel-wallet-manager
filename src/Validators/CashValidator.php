<?php


namespace Ashrafi\WalletManager\Validators;


use Ashrafi\WalletManager\Exceptions\CashValidatorException;

class CashValidator implements iValidator
{

    /**
     * @inheritDoc
     */
    public function validate($data = null)
    {
        if(!isset($data['user_id'])){
            throw new CashValidatorException('User id can\t be null in cash transaction');
        }
        if(!isset($data['wallet_id'])){
            throw new CashValidatorException('Wallet id can\t be null in cash transaction');
        }
    }
}
