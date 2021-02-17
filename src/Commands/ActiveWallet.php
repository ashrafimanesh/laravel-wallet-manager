<?php


namespace Ashrafi\WalletManager\Commands;


use Ashrafi\WalletManager\Events\WalletActived;
use Ashrafi\WalletManager\Models\Wallet;

class ActiveWallet extends WalletCommand
{
    /**
     * @var false
     */
    public $save;

    public function __construct($save = false, $dispatchEvent = true){
        parent::__construct($dispatchEvent);
        $this->save = $save;
    }

    public function handle(Wallet $wallet){
        $success = app(ModelCommands::class)->active($wallet, $this->save);
        if($success){
            $this->dispatchEvent(new WalletActived($wallet));
        }
        return $success;
    }
}
