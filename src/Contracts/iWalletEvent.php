<?php


namespace Ashrafi\WalletManager\Contracts;


use Ashrafi\WalletManager\Models\Wallet;

interface iWalletEvent
{
    public function getWallet():Wallet;
}
