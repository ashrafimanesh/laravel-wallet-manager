<?php


namespace Ashrafi\WalletManager\Events;


use Ashrafi\WalletManager\Models\Wallet;

class WalletBalanceChanged extends WalletEvent
{
    public $oldBalance;

    public function __construct(Wallet $wallet, $oldBalance)
    {
        parent::__construct($wallet);
        $this->oldBalance = $oldBalance;
    }
}
