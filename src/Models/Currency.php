<?php


namespace Ashrafi\WalletManager\Models;


/**
 * @property mixed symbol
 */
class Currency extends Model
{

    const DEFAULT = 'usd';

    protected $fillable = [
        'symbol',
        'status'
    ];

    public static function defaultSymbol()
    {
        return config('wallet_manager.currency.default', self::DEFAULT);
    }

    public function scopeActive($builder){
        return $builder->where('status', self::STATUS_ACTIVE);
    }

    public function scopeWhereSymbol($builder, $symbol){
        return $builder->where('symbol', strtolower($symbol));
    }

    public function isSame($symbol){
        return strtolower($symbol) === strtolower($this->symbol);
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return config('wallet_manager.database.prefix')."currencies";
    }

}
