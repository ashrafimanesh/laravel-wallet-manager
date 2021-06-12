<?php
/**
 * Created by PhpStorm.
 * User: ashrafimanesh@gmail.com
 * Date: 6/12/21
 * Time: 11:50 AM
 */

namespace Ashrafi\WalletManager\Contracts;

interface iRelatedTransactionTypeCreateRecord
{
    public function createRecord(array $data = null);
}