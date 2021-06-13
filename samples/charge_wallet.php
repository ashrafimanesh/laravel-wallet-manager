<?php
/**
 * Created by PhpStorm.
 * User: ashrafimanesh@gmail.com
 */

require_once __DIR__.'/../vendor/autoload.php';

$dispatchEvent = false;

$command = new \Ashrafi\WalletManager\Commands\ChangeWalletBalance($dispatchEvent);

//You can set $transactional to false if you begin transaction before!
$transactional = true;

/** @var \Ashrafi\WalletManager\Contracts\iUser $user */
$user = \Illuminate\Support\Facades\Auth::user();

$account = \Ashrafi\WalletManager\Facades\AccountModel::whereUser($user->getKey())->whereType('default')->first();
$currency = \Ashrafi\WalletManager\Facades\CurrencyModel::whereSymbol('USD')->first();

/** @var \Ashrafi\WalletManager\Models\Wallet $wallet */
$wallet = \Ashrafi\WalletManager\Facades\WalletModel::whereAccount($account->id)->whereCurrency($currency->id)->first();

//You can define transaction type here(By default supported transaction types is cash, bank_receipt, transfer and init, so if you want to add a new transaction type, first you need add it to transaction_types table and then extend TransactionType::createReference, TransactionType::staticGetValidator)
$type = \Ashrafi\WalletManager\Models\TransactionType::TYPE_CASH;
//You can set transaction status for update balance. If you don't have approve transaction you can set to approved for update balance otherwise status is pending to approve with another user.
$transactionStatus = \Ashrafi\WalletManager\Models\WalletTransaction::STATUS_APPROVED;
$transactionAmount = new \Ashrafi\WalletManager\ValueObjects\TransactionAmount(20, $type, $transactionStatus);

$command->handle($wallet, $transactionAmount, $transactional);
