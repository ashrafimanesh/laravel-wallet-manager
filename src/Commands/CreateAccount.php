<?php


namespace Ashrafi\WalletManager\Commands;


use Ashrafi\WalletManager\Events\AccountCreated;
use Ashrafi\WalletManager\Events\AccountCreating;
use Ashrafi\WalletManager\Exceptions\CommandException;
use Ashrafi\WalletManager\Exceptions\TransactionValidatorException;
use Ashrafi\WalletManager\Facades\AccountModel;
use Ashrafi\WalletManager\Facades\CurrencyModel;
use Ashrafi\WalletManager\Models\Account;
use Ashrafi\WalletManager\Models\Wallet;
use Illuminate\Support\Facades\DB;

class CreateAccount extends AccountCommand
{
    protected $commands = [];
    /**
     * @var bool
     */
    public $exist = null;

    /**
     * @param array $attributes
     * @param bool $transactional
     * @return Account|null
     * @throws CommandException|TransactionValidatorException
     */
    public function handle($attributes = [], $transactional = true)
    {

        if(!isset($attributes['type'])){
            $attributes['type'] = AccountModel::defaultType();
        }

        $this->validate($attributes);

        $this->dispatchEvent(new AccountCreating($attributes));

        if($transactional){
            DB::beginTransaction();
        }

        $account = $this->findOrCreateAccount($attributes);

        if(!$account){
            $this->addMessage("Can't create of find account");
            return null;
        }
        try{
            $this->createCurrenciesWallet($attributes, $account);
        }catch (CommandException $exception){
            $this->addMessage($exception->getMessage());
            return null;
        }

        if($transactional){
            DB::commit();
        }
        $this->dispatchEvent(new AccountCreated($account));
        return $account;
    }

    /**
     * @param Account $account
     * @param array $walletData
     * @param bool $transactional
     * @return mixed
     * @throws CommandException
     * @throws TransactionValidatorException
     */
    protected function createWallet(Account $account, array $walletData, $transactional = false)
    {

        $wallet = (new CreateWallet(false))->handle($walletData, $transactional);
        if(!$wallet){
            $this->addMessage("Can't create wallet or find it for user: {user_id}", ['user_id'=>$account->user_id, 'account_id'=>$account->id], 'error');
            throw new CommandException(last($this->getMessages('error')));
        }
        return $wallet;
    }

    /**
     * @param array $attributes
     * @throws CommandException
     */
    protected function validate(array $attributes)
    {
        if(!isset($attributes['name'])){
            throw new CommandException("Account name can't be null!");
        }
        if(!isset($attributes['user_id'])){
            throw new CommandException("Account user_id can't be null!");
        }
        if(isset($attributes['currencies']) && isset(array_values($attributes['currencies'])[0][0])){
            throw new CommandException("Invalid currencies to create account!");
        }

    }

    /**
     * @param array $attributes
     * @return AccountModel|null
     */
    protected function findOrCreateAccount(array $attributes)
    {
        $account = AccountModel::whereUnique($attributes)->first();
        if ($account) {
            $this->exist = true;
        }
        /** @var Account $account */
        if (!$account) {
            $this->exist = false;
            $account = AccountModel::create($attributes);
        }
        return $account;
    }

    /**
     * @param array $attributes
     * @param Account $account
     * @param bool $transactional
     * @throws CommandException
     * @throws TransactionValidatorException
     */
    protected function createCurrenciesWallet(array $attributes, Account $account, $transactional = false)
    {
        if (empty($attributes['currencies'])) {
            $attributes['currencies'] = [['symbol' => CurrencyModel::defaultSymbol()]];
        }
        $walletData = [
            'user_id' => $account->user_id,
            'account_id' => $account->id,
        ];
        foreach ($attributes['currencies'] as $currency) {
            $walletData['currency_id'] = $currency['currency_id'] ?? null;
            $walletData['symbol'] = $currency['symbol'] ?? null;
            if (isset($attributes['balance'])) {
                $walletData['balance'] = $attributes['balance'];
            } elseif (isset($currency['balance'])) {
                $walletData['balance'] = $currency['balance'];
            }
            $this->createWallet($account, $walletData, $transactional);
        }
    }
}
