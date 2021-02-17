<?php


namespace Ashrafi\WalletManager\Commands;


use Ashrafi\WalletManager\Events\WalletCreated;
use Ashrafi\WalletManager\Events\WalletCreating;
use Ashrafi\WalletManager\Exceptions\CommandException;
use Ashrafi\WalletManager\Exceptions\TransactionValidatorException;
use Ashrafi\WalletManager\Facades\CurrencyModel;
use Ashrafi\WalletManager\Facades\WalletModel;
use Ashrafi\WalletManager\Models\TransactionType;
use Ashrafi\WalletManager\Models\Wallet;
use Ashrafi\WalletManager\Models\WalletTransaction;
use Ashrafi\WalletManager\ValueObjects\TransactionAmount;
use Illuminate\Support\Facades\DB;

class CreateWallet extends WalletCommand
{

    /**
     * @var bool
     */
    public $exist = null;

    /**
     * @param array $attributes
     * @param bool $transactional
     * @return mixed
     * @throws CommandException
     * @throws TransactionValidatorException
     */
    public function handle($attributes = [], $transactional = true)
    {


        if (empty($attributes['currency_id'])) {
            $value = $attributes['symbol'] ?? CurrencyModel::defaultSymbol();
            $currency = CurrencyModel::query()->where('symbol', '=', $value)->first();
            if (!$currency) {
                $this->addMessage('Can\'t find currency to create wallet');
                return null;
            }
            $attributes['currency_id'] = $currency->id;
        }

        $this->validate($attributes);

        $attributes = $this->setDefaults($attributes);

        $this->dispatchEvent(new WalletCreating($attributes));

        /** @var Wallet $wallet */
        $wallet = WalletModel::whereUnique($attributes)->first();
        if ($wallet) {
            $this->exist = true;
            return $wallet;
        }

        if($transactional){
            DB::beginTransaction();
        }

        $balance = $attributes['balance'] ?? (config('wallet_manager.wallet.default_balance', 0));
        if(isset($attributes['balance'])){
            unset($attributes['balance']);
        }
        $wallet = WalletModel::create($attributes);
        if ($wallet) {
            $this->exist = false;
            $this->addBalanceTransaction($wallet, $balance, $attributes['transaction_type'] ?? null, $attributes['transaction_data'] ?? []);
            if($transactional){
                DB::commit();
            }
            $this->dispatchEvent(new WalletCreated($wallet));
        }
        return $wallet;
    }

    protected function setDefaults(array $attributes = []): array
    {
        return $attributes;
    }

    /**
     * @param array $attributes
     * @throws CommandException
     */
    protected function validate(array $attributes)
    {
        if (!isset($attributes['user_id'])) {
            throw new CommandException("Wallet user_id can't be null!");
        }
        if (!isset($attributes['currency_id'])) {
            throw new CommandException("Wallet currency_id can't be null!");
        }
        if (!isset($attributes['account_id'])) {
            throw new CommandException("Wallet account_id can't be null!");
        }
    }

    /**
     * @param Wallet $wallet
     * @param $balance
     * @param null $transactionType
     * @param array $transactionData
     * @param null $transactionStatus
     * @throws CommandException
     * @throws TransactionValidatorException
     */
    protected function addBalanceTransaction(Wallet $wallet, $balance, $transactionType = null, $transactionData = [], $transactionStatus = null)
    {
        if($balance<=0){
            return;
        }
        /** @var TransactionAmount $transactionAmount */
        $transactionAmount = app(TransactionAmount::class);
        $transactionAmount->amount = $balance;
        $transactionAmount->type = $transactionType ?? TransactionType::TYPE_INIT;
        $transactionAmount->data = $transactionData;
        if($transactionAmount->type == TransactionType::TYPE_INIT && !$transactionStatus){
            $transactionAmount->status = WalletTransaction::STATUS_APPROVED;
        }
        else{
            $transactionAmount->status = $transactionStatus ?? WalletTransaction::STATUS_PENDING;
        }

        /** @var ChangeWalletBalance $command */
        $command = app(ChangeWalletBalance::class);
        $walletTransaction = $command->handle($wallet, $transactionAmount, false);
        if($walletTransaction){
            $wallet->balance = $walletTransaction->calcTotalBalance();
        }
    }
}
