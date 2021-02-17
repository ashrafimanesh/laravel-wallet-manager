<?php


namespace Ashrafi\WalletManager\Events;


use Ashrafi\WalletManager\Models\WalletTransaction;

class WalletTransactionEvent extends Event
{

    /**
     * @var WalletTransaction
     */
    protected $walletTransaction;

    /**
     * Create a new event instance.
     *
     * @param WalletTransaction $walletTransaction
     */
    public function __construct(WalletTransaction $walletTransaction)
    {
        //
        $this->walletTransaction = $walletTransaction;
    }

    /**
     * @return WalletTransaction
     */
    public function getWalletTransaction(): WalletTransaction
    {
        return $this->walletTransaction;
    }

    public function toArray(){
        return $this->walletTransaction->toArray();
    }
}
