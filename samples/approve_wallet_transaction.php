<?php
/**
 * Created by PhpStorm.
 * User: ashrafimanesh@gmail.com
 */


require_once __DIR__.'/../vendor/autoload.php';

$dispatchEvent = false;

$command = new \Ashrafi\WalletManager\Commands\ApproveWalletTransaction($dispatchEvent);

//You can set $transactional to false if you begin transaction before!
$transactional = true;

/** @var \Ashrafi\WalletManager\Contracts\iUser $user */
$user = \Illuminate\Support\Facades\Auth::user();

$transactionId = 'transaction id here';
$walletTransaction = \Ashrafi\WalletManager\Facades\WalletTransactionModel::find($transactionId);

$command->handle($walletTransaction, $user, $transactional);
