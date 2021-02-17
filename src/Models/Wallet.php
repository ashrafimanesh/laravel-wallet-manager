<?php


namespace Ashrafi\WalletManager\Models;


use App\User;
use Ashrafi\WalletManager\Facades\AccountModel;
use Ashrafi\WalletManager\Facades\CurrencyModel;
use Ashrafi\WalletManager\Facades\WalletModel;
use Ashrafi\WalletManager\Facades\WalletTransactionModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property mixed balance
 * @property mixed user_id
 * @property Currency|mixed currency
 * @property mixed $freeze_balance
 * @property mixed credit
 * @property mixed config
 * @property Wallet|null parent
 * @property mixed account_id
 * @method static Builder userCurrency($userId, $currencyId)
 * @method static Builder whereUnique(array $attributes)
 */
class Wallet extends Model
{

    protected $fillable = [
        'user_id',
        'balance',
        'currency_id',
        'account_id',
        'status',
        'freeze_balance',
        'creator_id',
        'config',
        'locked_at',' locked_description', 'locked_by',
    ];

    protected $casts = [
        'config'=>'array',
        'locked_at'=>'datetime'
    ];

    protected $appends = [
        'credit'
    ];

    public static function defaultStatus()
    {
        return config('wallet_manager.wallet.default_status', \Ashrafi\WalletManager\Models\Wallet::STATUS_ACTIVE);
    }

    public function scopeUserCurrency($builder, $userId, $currencyId) {
        return $builder->whereUser($userId)->whereCurrency($currencyId);
    }

    public function scopeWhereUnique($builder, $data = null){
        return $builder->whereAccount($data['account_id'])->whereCurrency($data['currency_id']);
    }

    public function scopeWhereUser($builder, $userId){
        return $builder->where(WalletModel::getTable().'.user_id', $userId);
    }

    public function scopeWhereCurrency($builder, $currencyId){
        return $builder->where('currency_id', $currencyId);
    }

    public function scopeWhereAccount($builder, $accountId){
        return $builder->where('account_id', $accountId);
    }

    public function scopeActive($builder){
        return $builder->where('status', self::STATUS_ACTIVE)->whereNull('locked_at');
    }

    /**
     * @return mixed
     */
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getLockedAttribute(){
        return !empty($this->locked_at);
    }

    /**
     * @return mixed
     */
    public function currency()
    {
        return $this->belongsTo(CurrencyModel::getClass(), 'currency_id');
    }

    public function account(){
        return $this->belongsTo(AccountModel::getClass(), 'account_id');
    }

    public function transactions(){
        return $this->hasMany(WalletTransactionModel::getClass(), 'wallet_id');
    }

    public function desc_transactions(){
        return $this->hasMany(WalletTransactionModel::getClass(), 'wallet_id')->orderBy('id', 'desc');
    }

    public function getCreditAttribute(){
        return $this->balance - $this->freeze_balance;
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return config('wallet_manager.database.prefix')."wallets";
    }

    /**
     * @param $key
     * @param null $value
     * @return $this
     */
    public function setConfig($key, $value = null){
        if(empty($this->config)){
            $this->config = [];
        }
        $this->config[$key] = $value;
        return $this;
    }

}
