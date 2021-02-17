<?php


namespace Ashrafi\WalletManager\Events;


use Ashrafi\WalletManager\Models\Account;

class AccountConfigChanged extends AccountEvent
{

    public $key;
    /**
     * @var null
     */
    public $newValue;
    /**
     * @var null
     */
    public $oldValue;

    public function __construct(Account $account, $key, $newValue=null, $oldValue = null)
    {
        parent::__construct($account);

        $this->key = $key;
        $this->newValue = $newValue;
        $this->oldValue = $oldValue;
    }
}
