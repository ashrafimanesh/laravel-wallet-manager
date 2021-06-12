<?php


namespace Ashrafi\WalletManager\Models;


use Ashrafi\WalletManager\Contracts\iUser as User;
use Ashrafi\WalletManager\Facades\CurrencyModel;
use Ashrafi\WalletManager\Facades\WalletModel;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property mixed user_id
 * @property array|mixed wallets
 * @method static Builder whereUnique(mixed $attributes)
 * @property mixed config
 * @property mixed type
 */
class Account extends Model
{

    const TYPE_DEFAULT = 'default';

    protected $fillable = [
        'user_id',
        'type',
        'name',
        'config',
        'status',
        'locked_at',' locked_description', 'locked_by',
    ];

    protected $casts = [
        'config'=>'array',
        'locked_at'=>'datetime'
    ];

    public static function defaultType()
    {
        return config('wallet_manager.account.default_type', \Ashrafi\WalletManager\Models\Account::TYPE_DEFAULT);
    }

    public static function defaultStatus()
    {
        return config('wallet_manager.account.default_status', \Ashrafi\WalletManager\Models\Account::STATUS_ACTIVE);
    }

    public function scopeActive($builder){
        return $builder->where('status', self::STATUS_ACTIVE)->whereNull('locked_at');
    }

    public function scopeWhereUnique($builder, $data = null){
        return $builder->where(['name'=>$data['name'], 'user_id'=>$data['user_id'], 'type'=>$data['type'] ?? static::defaultType()]);
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeWhereUser($builder, $user){
        return $builder->where(['user_id' => ($user instanceof User) ? $user->id : $user]);
    }

    public function wallets(){
        return $this->hasMany(WalletModel::getClass(), 'account_id');
    }

    public function walletBalance($symbol = null){
        if($symbol){
            $currency = CurrencyModel::active()->whereSymbol($symbol)->first();
            if(!$currency){
                return 0;
            }
            $wallet = WalletModel::active()->whereAccount($this->id)->whereCurrency($currency->id)->first();
            if(!$wallet){
                return 0;
            }
            return $wallet->balance;
        }
        $wallets = WalletModel::active()->whereAccount($this->id)->get();
        $results = [];
        foreach($wallets as $wallet){
            if($wallet->currency){
                $results[$wallet->currency->symbol] = $wallet->balance;
            }
        }
        return $results;
    }

    public function getConfig($field, $default = null){
        return isset($this->config[$field]) ? $this->config[$field] : $default;
    }

    public function getTable()
    {
        return config('wallet_manager.database.prefix')."accounts";
    }

}
