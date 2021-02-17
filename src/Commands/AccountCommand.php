<?php


namespace Ashrafi\WalletManager\Commands;


use Ashrafi\WalletManager\Events\AccountEvent;
use Ashrafi\WalletManager\Events\Event;

class AccountCommand extends Command
{

    public function dispatchEvent(Event $event){
        $this->addEvent($event);
        if(!$this->dispatchEvent){
            return;
        }
        if($event instanceof AccountEvent){
            $event->visitCommand($this);
        }
        event($event);
    }
}
