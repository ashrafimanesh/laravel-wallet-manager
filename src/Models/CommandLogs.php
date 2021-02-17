<?php


namespace Ashrafi\WalletManager\Models;


class CommandLogs extends Model
{
    protected $fillable = [
        'model_type', 'model_id',
        'owner_id',
        'entity_type','entity_data'
    ];

    protected $casts = [
        'entity_data'=>'array'
    ];

    /**
     * @return string
     */
    public function getTable()
    {
        return config('wallet_manager.database.prefix')."command_logs";
    }

}
