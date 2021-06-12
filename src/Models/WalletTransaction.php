<?php


namespace Ashrafi\WalletManager\Models;


use Ashrafi\WalletManager\Contracts\iUser as User;
use Ashrafi\WalletManager\Facades\AccountModel;
use Ashrafi\WalletManager\Facades\TransactionTypeModel;
use Ashrafi\WalletManager\Facades\WalletModel;
use Ashrafi\WalletManager\Facades\WalletTransactionModel;
use Illuminate\Support\Facades\DB;

/**
 * @property mixed reference
 * @property mixed user_id
 * @property mixed account_id
 * @property mixed wallet_id
 * @property mixed payload
 * @property mixed amount
 * @property mixed related_to
 * @property WalletTransaction|null related
 * @property Wallet wallet
 * @method static pending()
 */
class WalletTransaction extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';

    protected $fillable = [
        'user_id',
        'account_id',
        'wallet_id',
        'transaction_type_id',
        'amount',
        'status',
        'reference_type',
        'reference_id',
        'related_to',
        'payload'
    ];

    protected $casts = [
        'payload'=>'array'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function account(){
        return $this->belongsTo(AccountModel::getClass(), 'account_id');
    }

    public function transactionType(){
        return $this->belongsTo(TransactionTypeModel::getClass(), 'transaction_type_id');
    }

    public function wallet(){
        return $this->belongsTo(WalletModel::getClass(), 'wallet_id');
    }

    public function related()
    {
        return $this->belongsTo(WalletTransactionModel::getClass(), 'related_to');
    }

    public function scopeWhereReference($builder, $reference){
        return $builder->whereReferenceType(get_class($reference))->whereReferenceId($reference->id);
    }

    public function scopeWhereReferenceType($builder, $referenceType){
        return $builder->where('reference_type', $referenceType);
    }

    public function scopeWhereReferenceId($builder, $referenceId){
        return $builder->where('reference_id', $referenceId);
    }

    public function scopeApproved($builder){
        return $builder->where('status', static::STATUS_APPROVED);
    }

    public function scopePending($builder){
        return $builder->where('status', static::STATUS_PENDING);
    }

    public function getReferenceAttribute(){
        if($this->reference_type && $this->reference_id && class_exists($this->reference_type)){
            $class = $this->reference_type;
            return $class::find($this->reference_id);
        }
        return null;
    }

    public function recalculateBalance()
    {
        $balance = $this->calcTotalBalance();
        WalletModel::query()->where('id', $this->wallet_id)->update(['balance' => $balance]);
        return WalletModel::find($this->wallet_id);
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return config('wallet_manager.database.prefix')."wallet_transactions";
    }

    public function calcTotalBalance()
    {
        return static::staticCalcTotalBalance($this->wallet_id);
    }

    public function isApproved()
    {
        return $this->status == static::STATUS_APPROVED;
    }

    public function isPending()
    {
        return $this->status == static::STATUS_PENDING;
    }

    public function setPayload(string $field, $value = null)
    {
        $payload = $this->payload ?? [];
        $payload[$field] = $value;
        $this->payload = $payload;
        return $this;
    }

    public static function staticCalcTotalBalance($walletId)
    {
        $obj = WalletTransactionModel::approved()->where(['wallet_id' => $walletId])->select(DB::raw('SUM(amount) as total_amount'))->first();
        return $obj ? ($obj->total_amount ?: 0) : 0;
    }

}
