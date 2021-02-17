# Laravel Wallet Management Package
Wallet Manager can manage user finance with multi accounts and wallets feature.

User can have multi accounts for any purpose of your application. For trading applications you can create a demo account, real account and etc. Each account can has multi wallets to handle your currencies.



### Requirements

php: >=7.1

"laravel/framework": "^5.0|^6.0|^7.0|^8.0"

### Installation

```php
composer require ashrafi/wallet-manager:^v0.1

```
if you want to publish config you can run:
```php
php artisan vendor:publish --tag=wallet-manager-config
```

Next you need to run migrate to create database tables:
```php
php artisan migrate
```

* NOTICE: This package will autoload with laravel and you don't need to add `\Ashrafi\WalletManager\ServiceProvider::class` to your bootstrap.


### Structure

* Facades:

  This package use facade pattern for models and you can extend and customize themes.

  To extend models you need add below codes to your `AppServiceProvider`:

    ```php
    use Ashrafi\WalletManager\Models\Wallet;

    //YOUR CODES
    
    public function register(){
        
        //YOUR CODES
        
        $this->app->extend(Wallet::class, function(){
            return new {YourExtendedClass};
        });

    }
    ```


**IMPORTANT**

Don't use WalletManager models directly and just use their facades: `WalletModel, AccountModel, CurrencyModel,...`

---

* Events:

  The package have WalletEvent and AccountEvent to do anything you need to handle.

    * `WalletCreating`: triggered before create the wallet and after validate input.
    * `WalletCreated`: triggered after create the wallet.

    * `AccountCreating`: triggered before create the account and after validate input.
    * `AccountCreated`: triggered after create the account.
