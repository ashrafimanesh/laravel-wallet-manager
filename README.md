# Laravel Wallet Management Package
Wallet Manager can manage user finance with multi accounts and wallets feature.

User can have multi accounts for any purpose of your application. For trading applications you can create a demo account, real account and etc. Each account can has multi wallets to handle your currencies.

### Requirements

php: >=7.1

"laravel/framework": "^5.0|^6.0|^7.0|^8.0"

### Installation

```php
composer require ashrafi/laravel-wallet:^v0.1
```
if you want to publish config you can run:
```php
php artisan vendor:publish --tag=wallet-manager-config
```

publish seeders
```php
php artisan vendor:publish --tag=wallet-manager-seeder
```
Add seeders to `DatabaseSeeder.php`
```php
(new CurrencySeeder())->run();
(new TransactionTypeSeeder())->run();
```


*NOTICE:*
We use `\App\User` or `\App\Models\User` class to relate tables to the user and you can bind it to other class that implement iUser::class and bind it on `AppServiceProvider`

Next you need to run migrate and db:seed to create database tables and seeds:

```php
php artisan migrate
php artisan db:seed
```

*NOTICE:*
This package will autoload with laravel and you don't need to add `\Ashrafi\WalletManager\ServiceProvider::class` to your bootstrap.

### Structure

* Facades:
  This package use facade pattern for models and you can extend and customize themes.
  To extend models you need add below codes to your `AppServiceProvider`:

    ```php
    use Ashrafi\WalletManager\Models\Wallet;

    //YOUR CODES
    
    public function register(){
        //YOUR CODES
        //Bind other User class if you don't use \App\User class for authentication
        $this->app->bind(iUser::class, function(){
            return new {YourExtendedClass};
        });
        
        $this->app->extend(Wallet::class, function(){
            return new {YourExtendedClass};
        });

    }
    ```
* Commands:
    List of commands are located in src/Commands and you need to use this commands to work with this package features!
    

**IMPORTANT**
Don't use WalletManager models directly and just use their facades: `WalletModel, AccountModel, CurrencyModel,...`
---

* Events:

  The package have WalletEvent and AccountEvent to do anything you need to handle.

    * `WalletCreating`: triggered before create the wallet and after validate input.
    * `WalletCreated`: triggered after create the wallet.

    * `AccountCreating`: triggered before create the account and after validate input.
    * `AccountCreated`: triggered after create the account.

#### Usages
List of samples are located in samples folder, but you can follow them with this sort:
'transfer', etc).
* Create Account
    This command will create an account with multi wallets for given user_id.
    Exp: You cann create a account for financial or scoring purpose! 

* Change wallet balance
    This command will charge/withdrawal a wallet. You can create `TransactionAmount` object which keep +/-amount, type and status.
* Approve transaction
    This command will approve transaction and recalculate wallet(s) balance(s).

##### Value objects

*  TransactionAmount
    Attribute | Type | Default | Description
    --- | --- | --- | ---
    amount | double | 0 | posetive amount for charge wallet and negative amount for withdraw
    type | string | cash | Describe type of the transaction. This value should be find in  transaction_types table.
    status | string | pending | (`pending` or `approved`) If status is on `pending` value, you need to approve the transaction to unlock freeze amount and change wallet balance. If status is on `approved` value, you don't need to approve it in panel and this transaction will create with approved status and wallet balance will change automatically.

##### Adding new transaction type
Each transaction can has seperate table for store related transaction data and validate related data, if so:
1. Add your new type to transaction_types table.
2. create new `model/class` for it and implement `iRelatedTransactionTypeValidate` class to validate transaction data and implement `iRelatedTransactionTypeCreateRecord` to insert transaction data to your table.
3. add new type to `wallet_manager.transaction_types.types` config file.

**Important transaction types**
1. `init`: This type already is defined and used on create new wallet with init balance.
2. `transfer`: This type already is defined and used on transfer a amount from a wallet to an other wallet.
3. `cash`: This type already is defined and used on charge or withdraw a cash amount without any extra data. We suggest to you define a new type to handle charge/withdraw transaction to store related data
