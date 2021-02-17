<?php


namespace Ashrafi\WalletManager\Commands;


use Ashrafi\WalletManager\Events\AccountLocked;
use Ashrafi\WalletManager\Models\Account;

class LockAccount extends AccountCommand
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
     * @param Account $account
     * @return bool
     */
    public function handle(Account $account){
        $success = app(ModelCommands::class)->lock($account, $this->lockedBy, $this->lockDescription, $this->save);
        if($success){
            $this->dispatchEvent(new AccountLocked($account));
        }
        return $success;
    }
}
