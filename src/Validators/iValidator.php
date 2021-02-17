<?php


namespace Ashrafi\WalletManager\Validators;


use Ashrafi\WalletManager\Exceptions\TransactionValidatorException;

interface iValidator
{
    /**
     * @param null $data
     * @throws TransactionValidatorException
     */
    public function validate($data = null);
}
