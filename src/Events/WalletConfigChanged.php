<?php


namespace Ashrafi\WalletManager\Events;


use Ashrafi\WalletManager\Models\Wallet;

class WalletConfigChanged extends WalletEvent
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

    public function __construct(Wallet $wallet, $key, $newValue=null, $oldValue = null)
    {
        parent::__construct($wallet);

        $this->key = $key;
        $this->newValue = $newValue;
        $this->oldValue = $oldValue;
    }
}
