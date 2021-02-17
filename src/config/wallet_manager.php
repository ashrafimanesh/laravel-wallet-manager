<?php

return [
    'database'=>[
        'prefix'=>'wm_',
    ],
    'currency'=>[
        'default'=>\Ashrafi\WalletManager\Models\Currency::DEFAULT
    ],
    'wallet'=>[
        'default_balance'=>0,
        'default_status'=>\Ashrafi\WalletManager\Models\Wallet::STATUS_ACTIVE,
    ],
    'account_wallet'=>[
    ],
    'account'=>[
        'default_type'=>\Ashrafi\WalletManager\Models\Account::TYPE_DEFAULT,
        'default_status'=>\Ashrafi\WalletManager\Models\Account::STATUS_ACTIVE,
    ]
];
