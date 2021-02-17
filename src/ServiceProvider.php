<?php


namespace Ashrafi\WalletManager;


use Ashrafi\WalletManager\Models\Account;
use Ashrafi\WalletManager\Models\TransactionTypes\BankReceiptTransaction;
use Ashrafi\WalletManager\Models\TransactionTypes\CashTransaction;
use Ashrafi\WalletManager\Models\Currency;
use Ashrafi\WalletManager\Models\TransactionType;
use Ashrafi\WalletManager\Models\TransactionTypes\TransferTransaction;
use Ashrafi\WalletManager\Models\Wallet;
use Ashrafi\WalletManager\Models\WalletTransaction;
use Ashrafi\WalletManager\Models\WalletTransactionLog;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    public function boot(){
        $this->publishes([
            __DIR__.'/config/wallet_manager.php'=>config_path('wallet_manager.php')
        ], 'wallet-manager-config');

        $this->publishes([
            __DIR__.'/migrations/' => database_path('migrations')
        ], 'wallet-manager-migrations');

    }

    public function register()
    {
        $this->app->bind(Wallet::class, function(){
            return new Wallet();
        });

        $this->app->bind(Account::class, function(){
            return new Account();
        });

        $this->app->bind(Currency::class, function(){
            return new Currency();
        });

        $this->app->bind(TransactionType::class, function(){
            return new TransactionType();
        });

        $this->app->bind(WalletTransaction::class, function(){
            return new WalletTransaction();
        });

        $this->app->bind(WalletTransactionLog::class, function(){
            return new WalletTransactionLog();
        });

        $this->app->bind(BankReceiptTransaction::class, function(){
            return new BankReceiptTransaction();
        });

        $this->app->bind(CashTransaction::class, function(){
            return new CashTransaction();
        });

        $this->app->bind(TransferTransaction::class, function(){
            return new TransferTransaction();
        });
    }
}
