<?php


namespace Ashrafi\WalletManager\Models\TransactionTypes;


use Ashrafi\WalletManager\Contracts\iRelatedTransactionTypeCreateRecord;
use Ashrafi\WalletManager\Contracts\iRelatedTransactionTypeValidate;
use Ashrafi\WalletManager\Contracts\iUser as User;
use Ashrafi\WalletManager\Exceptions\CashValidatorException;
use Ashrafi\WalletManager\Facades\WalletModel;
use Ashrafi\WalletManager\Models\Model;
use Ashrafi\WalletManager\Models\WalletTransaction;
use Illuminate\Support\Arr;

/**
 * @property mixed changed_by
 */
class CashTransaction extends Model implements iRelatedTransactionTypeCreateRecord, iRelatedTransactionTypeValidate
{

    protected $fillable = [
        'user_id', 'wallet_id', 'amount', 'pay_date', 'creator_id', 'payload', 'status', 'changed_by'
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

    public function createRecord(array $data = null){
        return static::create(Arr::only($data, $this->fillable) + ['payload'=>Arr::except($data, $this->fillable)]);
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return config('wallet_manager.database.prefix')."cash_transactions";
    }

    public function validateRecord($data = null)
    {
        if(!isset($data['user_id'])){
            throw new CashValidatorException('User id can\t be null in cash transaction');
        }
        if(!isset($data['wallet_id'])){
            throw new CashValidatorException('Wallet id can\t be null in cash transaction');
        }
    }
}
