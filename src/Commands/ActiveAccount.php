<?php


namespace Ashrafi\WalletManager\Commands;


use Ashrafi\WalletManager\Events\AccountActived;
use Ashrafi\WalletManager\Models\Account;

class ActiveAccount extends AccountCommand
{
    /**
     * @var false
     */
    public $save;

    public function __construct($save = false, $dispatchEvent = true){
        parent::__construct($dispatchEvent);
        $this->save = $save;
    }

    public function handle(Account $account){
        $success = app(ModelCommands::class)->active($account, $this->save);
        if($success){
            $this->dispatchEvent(new AccountActived($account));
        }
        return $success;
    }
}
