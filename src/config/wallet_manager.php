<?php

use Ashrafi\WalletManager\Models\TransactionType;
use Ashrafi\WalletManager\Models\TransactionTypes\BankReceiptTransaction;
use Ashrafi\WalletManager\Models\TransactionTypes\CashTransaction;
use Ashrafi\WalletManager\Models\TransactionTypes\TransferTransaction;

return [
    'database' => [
        'prefix' => 'wm_',
    ],
    'currency' => [
        'default' => \Ashrafi\WalletManager\Models\Currency::DEFAULT_SYMBOL
    ],
    'wallet' => [
        'default_balance' => 0,
        'default_status' => \Ashrafi\WalletManager\Models\Wallet::STATUS_ACTIVE,
        'minimum_balance' => 0
    ],
    'transaction_type' => [
        'types' => [
            TransactionType::TYPE_CASH => ['handler' => CashTransaction::class],
            TransactionType::TYPE_BANK_RECEIPT => ['handler' => BankReceiptTransaction::class],
            TransactionType::TYPE_TRANSFER => ['handler' => TransferTransaction::class],
        ]
    ],
    'account_wallet' => [
    ],
    'account' => [
        'default_type' => \Ashrafi\WalletManager\Models\Account::TYPE_DEFAULT,
        'default_status' => \Ashrafi\WalletManager\Models\Account::STATUS_ACTIVE,
    ]
];
