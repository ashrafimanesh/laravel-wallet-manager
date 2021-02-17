<?php


namespace Ashrafi\WalletManager\Models;


use Ashrafi\WalletManager\Facades\WalletTransactionModel;

class WalletTransactionLog extends Model
{

    protected $fillable = [
        'wallet_transaction_id',
        'field',
        'from_value', 'to_value',
        'done_by'
    ];


    public function walletTransaction(){
        return $this->belongsTo(WalletTransactionModel::getClass(), 'wallet_transaction_id');
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return config('wallet_manager.database.prefix')."wallet_transaction_logs";
    }

}
