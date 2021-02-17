<?php


namespace Ashrafi\WalletManager\Commands;


use Ashrafi\WalletManager\Events\AccountConfigChanged;
use Ashrafi\WalletManager\Models\Account;

class SetAccountConfig extends AccountCommand
{
    public $key;
    /**
     * @var null
     */
    public $value;
    /**
     * @var false
     */
    public $save;

    /**
     * SetWalletConfig constructor.
     * @param $key
     * @param null $value
     * @param false $save
     * @param bool $dispatchEvent
     */
    public function __construct($key, $value = null, $save = false, $dispatchEvent = true)
    {
        parent::__construct($dispatchEvent);
        $this->key = $key;
        $this->value = $value;
        $this->save = $save;
    }

    /**
     * @param Account $account
     * @return bool
     */
    public function handle(Account $account)
    {
        $oldValue = $account->setIndexToArrayCast('config', $this->key, $this->value);
        $success = true;
        if ($this->save) {
            $success = $account->save();
        }
        if ($success && ($oldValue !== $this->value)) {
            $this->dispatchEvent(new AccountConfigChanged($account, $this->key, $this->value, $oldValue));
        }
        return $success;
    }
}
