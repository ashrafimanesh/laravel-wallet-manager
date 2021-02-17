<?php


namespace Ashrafi\WalletManager\Events;


use Ashrafi\WalletManager\Commands\AccountCommand;
use Ashrafi\WalletManager\Models\Account;

class AccountEvent extends Event
{
    /**
     * @var Account
     */
    public $account;
    /**
     * @var AccountCommand
     */
    protected $command;

    public function __construct(Account $account){
        $this->account = $account;
    }

    public function visitCommand(AccountCommand $command)
    {
        $this->command = $command;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

}
