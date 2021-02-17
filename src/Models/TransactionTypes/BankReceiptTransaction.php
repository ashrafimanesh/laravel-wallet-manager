<?php


namespace Ashrafi\WalletManager\Models\TransactionTypes;


use App\User;
use Ashrafi\WalletManager\Facades\WalletModel;
use Ashrafi\WalletManager\Models\Model;
use Ashrafi\WalletManager\Models\WalletTransaction;
use Illuminate\Support\Arr;

/**
 * Class BankReceiptTransaction
 * @property mixed changed_by
 * @package Ashrafi\WalletManager\Models
 */
class BankReceiptTransaction extends Model
{

    protected $fillable = [
        'user_id', 'wallet_id', 'amount', 'bank_name', 'pay_date', 'serial', 'creator_id', 'payload', 'status', 'changed_by'
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

    public function creator(){
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function wallet(){
        return $this->belongsTo(WalletModel::getClass(), 'wallet_id');
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
        return config('wallet_manager.database.prefix')."bank_receipt_transactions";
    }

}
