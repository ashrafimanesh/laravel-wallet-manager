<?php


namespace Ashrafi\WalletManager\Events;


use Ashrafi\WalletManager\Commands\WalletCommand;
use Ashrafi\WalletManager\Contracts\iWalletEvent;
use Ashrafi\WalletManager\Models\Wallet;

class WalletEvent extends Event implements iWalletEvent
{
    /**
     * @var Wallet
     */
    public $wallet;
    /**
     * @var WalletCommand
     */
    protected $command;

    public function __construct(Wallet $wallet){
        $this->wallet = $wallet;
    }

    public function visitCommand(WalletCommand $command)
    {
        $this->command = $command;
    }

    public function getWallet(): Wallet
    {
        return $this->wallet;
    }


}
