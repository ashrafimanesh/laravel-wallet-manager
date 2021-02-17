<?php


namespace Ashrafi\WalletManager\Traits;


use Ashrafi\WalletManager\Facades\AccountModel;
use Ashrafi\WalletManager\Facades\WalletModel;
use Ashrafi\WalletManager\Models\Wallet;

/**
 * Trait UserWallets
 * @package Ashrafi\WalletManager\Traits
 * @property Wallet first_wallet
 */
trait UserWallets
{
    public function wallets(){
        return $this->hasMany(WalletModel::getClass(), 'user_id');
    }

    public function accounts(){
        return $this->hasMany(AccountModel::getClass(), 'user_id');
    }

    public function getFirstWalletAttribute(){
        return WalletModel::active()->whereUser($this->id)->first();
    }

    public function hasAccountType($type){
        return AccountModel::query()->whereUser($this)->whereType($type)->exists();
    }

    public function accountTypeCount($type){
        return AccountModel::query()->whereUser($this)->whereType($type)->count();
    }
}
