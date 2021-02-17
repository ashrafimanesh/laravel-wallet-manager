<?php


namespace Ashrafi\WalletManager\Models\TransactionTypes;


use App\User;
use Ashrafi\WalletManager\Facades\WalletModel;
use Ashrafi\WalletManager\Models\Model;
use Ashrafi\WalletManager\Models\WalletTransaction;
use Illuminate\Support\Arr;

/**
 * Class TransferTransaction
 * @package Ashrafi\WalletManager\Models\TransactionTypes
 *
 * @property mixed wallet_id
 * @property mixed second_wallet_id
 * @property mixed amount
 * @property mixed second_amount
 * @property mixed payload
 * @property mixed status
 * @property mixed changed_by
 */
class TransferTransaction extends Model
{

    protected $fillable = [
        'user_id', 'wallet_id', 'second_wallet_id', 'amount', 'second_amount', 'payload', 'status', 'changed_by'
    ];

    protected $dates = [
        'pay_date'
    ];

    protected $casts = [
        'payload'=>'array'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function fromWallet(){
        return $this->belongsTo(WalletModel::getClass(), 'wallet_id');
    }

    public function toWallet(){
        return $this->belongsTo(WalletModel::getClass(), 'to_wallet_id');
    }

    public function done(User $doneBy)
    {
        $this->status = WalletTransaction::STATUS_APPROVED;
        $this->changed_by = $doneBy->id;
        $this->save();
    }

    public function createRecord($data){
        return static::create(Arr::only($data, $this->fillable) + ['payload'=>Arr::except($data, $this->fillable)]);
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return config('wallet_manager.database.prefix')."transfer_transactions";
    }
}
