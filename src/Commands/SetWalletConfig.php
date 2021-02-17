<?php


namespace Ashrafi\WalletManager\Commands;


use Ashrafi\WalletManager\Events\WalletConfigChanged;
use Ashrafi\WalletManager\Models\Wallet;

class SetWalletConfig extends WalletCommand
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
     * @param Wallet $wallet
     * @return bool
     */
    public function handle(Wallet $wallet)
    {
        $oldValue = $wallet->setIndexToArrayCast('config', $this->key, $this->value);
        $success = true;
        if ($this->save) {
            $success = $wallet->save();
        }
        if ($success && ($oldValue !== $this->value)) {
            $this->dispatchEvent(new WalletConfigChanged($wallet, $this->key, $this->value, $oldValue));
        }
        return $success;
    }
}
