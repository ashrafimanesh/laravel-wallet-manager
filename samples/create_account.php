<?php
/**
 * Created by PhpStorm.
 * User: ashrafimanesh@gmail.com
 */

require_once __DIR__.'/../vendor/autoload.php';

$dispatchEvent = false;

$command = new \Ashrafi\WalletManager\Commands\CreateAccount($dispatchEvent);

//You can set $transactional to false if you begin transaction before!
$transactional = true;

//You can set default balance for wallet
$defaultBalance = 0;

$account = $command->handle([
    "name"=>"User account name here",
    "user_id"=> \Illuminate\Support\Facades\Auth::user()->id,
    'type'=> 'default',//user_id and type is unique in application
    "currencies"=>[
        //You can set user account default currencies or symbols
        new \Ashrafi\WalletManager\ValueObjects\WalletCurrency('USD', null, $defaultBalance),
        new \Ashrafi\WalletManager\ValueObjects\WalletCurrency('IRR', null, $defaultBalance),
    ]
], $transactional);

