<?php


namespace Ashrafi\WalletManager;


use Ashrafi\WalletManager\Events\AccountEvent;
use Ashrafi\WalletManager\Events\WalletEvent;
use Ashrafi\WalletManager\Events\WalletTransactionEvent;
use Ashrafi\WalletManager\Models\CommandLogs;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Auth;

class WalletEventSubscriber
{
    /**
     * Register the listeners for the subscriber.
     *
     * @param  Dispatcher  $events
     * @return void
     */
    public function subscribe($events)
    {
        $events->listen('Ashrafi\\WalletManager\Events\\*', function($eventName, $params = []){
            $this->handle($params[0]);
        });
    }

    public function handle($event)
    {
        if(!is_object($event)){
            return;
        }
        if(($event instanceof WalletEvent)){
            $this->handleWalletEvent($event);
        }
        if(($event instanceof WalletTransactionEvent)){
            $this->handleWalletTransactionEvent($event);
        }
        if($event instanceof AccountEvent){
            $this->handleAccountEvent($event);
        }
    }

    protected function handleWalletEvent(WalletEvent $event)
    {
        $wallet = $event->getWallet();
        return CommandLogs::create([
            'model_id'=>$wallet->id,
            'model_type'=>get_class($wallet),
            'entity_type'=>get_class($event),
            'entity_data'=>$event,
            'owner_id'=>$wallet->user_id
        ]);
    }

    protected function handleWalletTransactionEvent(WalletTransactionEvent $event)
    {
        $walletTransaction = $event->getWalletTransaction();
        return CommandLogs::create([
            'model_id'=> $walletTransaction->id,
            'model_type'=>get_class($walletTransaction),
            'entity_type'=>get_class($event),
            'entity_data'=>$event,
            'owner_id'=>$walletTransaction->user_id
        ]);
    }

    protected function handleAccountEvent(AccountEvent $event)
    {
        $account = $event->getAccount();
        return CommandLogs::create([
            'model_id'=>$account->id,
            'model_type'=>get_class($account),
            'entity_type'=>get_class($event),
            'entity_data'=>method_exists($event, 'toArray') ? $event->toArray() : $event,
            'owner_id'=>$account->user_id
        ]);
    }

}
