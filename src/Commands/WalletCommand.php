<?php


namespace Ashrafi\WalletManager\Commands;



use Ashrafi\WalletManager\Events\Event;
use Ashrafi\WalletManager\Events\WalletEvent;

class WalletCommand extends Command
{

    public function dispatchEvent(Event $event){
        $this->addEvent($event);
        if(!$this->dispatchEvent){
            return;
        }
        if($event instanceof WalletEvent){
            $event->visitCommand($this);
        }
        event($event);
    }
}
