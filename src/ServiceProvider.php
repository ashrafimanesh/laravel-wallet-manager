<?php


namespace Ashrafi\WalletManager;


use Ashrafi\WalletManager\Contracts\iUser;
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
            __DIR__.'/seeders/CurrencySeeder.php'=>database_path('seeders/CurrencySeeder.php'),
            __DIR__.'/seeders/TransactionTypeSeeder.php'=>database_path('seeders/TransactionTypeSeeder.php')
        ], 'wallet-manager-seeder');

        $this->loadMigrationsFrom([
            __DIR__.'/migrations/'
        ]);

    }

    public function register()
    {
        $this->app->bind(iUser::class, function(){
            $userClasses = ['App\User', 'App\Models\User'];
            foreach($userClasses as $class){
                if(class_exists($class)){
                    return new $class;
                }
            }
            throw new \Exception("User class not found please bind iUser::class to a user model!");
        });

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
