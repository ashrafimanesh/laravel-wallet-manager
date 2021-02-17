<?php


namespace Ashrafi\WalletManager\Facades;

/**
 * Class Facade
 * @package Ashrafi\WalletManager\Facades
 */
class Facade extends \Illuminate\Support\Facades\Facade
{
    public static function getClass()
    {
        return get_class(app()->make(static::getFacadeAccessor()));
    }

}
