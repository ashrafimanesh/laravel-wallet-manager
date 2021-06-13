<?php
/**
 * Created by PhpStorm.
 * User: ashrafimanesh@gmail.com
 */

namespace Ashrafi\WalletManager\Factories;

class TransactionTypeFactory
{
    public function make($type)
    {
        $types = config('wallet_manager.transaction_type.types');
        if (empty($types[$type]) || empty($types[$type]['handler'])) {
            return null;
        }
        return app($types[$type]['handler'], $types[$type]['params'] ?? []);
    }
}