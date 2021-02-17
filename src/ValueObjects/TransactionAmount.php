<?php


namespace Ashrafi\WalletManager\ValueObjects;


use Ashrafi\WalletManager\Models\TransactionType;
use Ashrafi\WalletManager\Models\WalletTransaction;

class TransactionAmount
{
    public $amount = 0;
    public $type = TransactionType::TYPE_CASH;
    public $data = [];
    public $status = WalletTransaction::STATUS_PENDING;

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'amount'=>$this->amount,
            'type'=>$this->type,
            'status'=>$this->status,
            'data'=>$this->data
        ];
    }
}
