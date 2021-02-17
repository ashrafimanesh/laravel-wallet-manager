<?php


namespace Ashrafi\WalletManager\Commands;


use Ashrafi\WalletManager\Events\WalletLocked;
use Ashrafi\WalletManager\Models\Wallet;

class LockWallet extends WalletCommand
{
    /**
     * @var null
     */
    public $lockedBy;
    /**
     * @var string
     */
    public $lockDescription;
    /**
     * @var false
     */
    public $save;

    /**
     * LockWallet constructor.
     * @param null $lockedBy
     * @param string $lockDescription
     * @param false $save
     * @param bool $dispatchEvent
     */
    public function __construct($lockedBy=null, $lockDescription = '', $save = false, $dispatchEvent = true){
        parent::__construct($dispatchEvent);
        $this->lockedBy = $lockedBy;
        $this->lockDescription = $lockDescription;
        $this->save = $save;
    }

    /**
     * @param Wallet $wallet
     * @return bool
     */
    public function handle(Wallet $wallet){
        $success = app(ModelCommands::class)->lock($wallet, $this->lockedBy, $this->lockDescription, $this->save);
        if($success){
            $this->dispatchEvent(new WalletLocked($wallet));
        }
        return $success;
    }
}
