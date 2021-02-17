<?php


namespace Ashrafi\WalletManager\Events;


use Ashrafi\WalletManager\Models\WalletTransaction;

class BalanceTransferred extends Event
{
    /**
     * @var WalletTransaction
     */
    private $withdrawTransaction;
    /**
     * @var WalletTransaction
     */
    private $depositTransaction;

    public function __construct(WalletTransaction $withdrawTransaction, WalletTransaction $depositTransaction)
    {

        $this->withdrawTransaction = $withdrawTransaction;
        $this->depositTransaction = $depositTransaction;
    }

    /**
     * @return WalletTransaction
     */
    public function getWithdrawTransaction(): WalletTransaction
    {
        return $this->withdrawTransaction;
    }

    /**
     * @return WalletTransaction
     */
    public function getDepositTransaction(): WalletTransaction
    {
        return $this->depositTransaction;
    }
}
