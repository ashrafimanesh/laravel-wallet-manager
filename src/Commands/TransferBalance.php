<?php


namespace Ashrafi\WalletManager\Commands;


use Ashrafi\WalletManager\Events\BalanceTransferred;
use Ashrafi\WalletManager\Exceptions\CommandException;
use Ashrafi\WalletManager\Exceptions\NoEnoughBalanceException;
use Ashrafi\WalletManager\Exceptions\TransactionValidatorException;
use Ashrafi\WalletManager\Models\TransactionType;
use Ashrafi\WalletManager\Models\Wallet;
use Ashrafi\WalletManager\ValueObjects\TransactionAmount;
use Illuminate\Support\Facades\DB;

class TransferBalance extends Command
{

    /**
     * @param Wallet $fromWallet
     * @param Wallet $toWallet
     * @param TransactionAmount $fromAmount
     * @param TransactionAmount $toAmount
     * @param bool $transactional
     * @return array
     * @throws NoEnoughBalanceException
     * @throws CommandException
     * @throws TransactionValidatorException
     */
    public function handle(Wallet $fromWallet, Wallet $toWallet, TransactionAmount $fromAmount, TransactionAmount $toAmount, $transactional = true)
    {
        if($transactional){
            DB::beginTransaction();
        }
        $fromAmount->data[ 'second_wallet_id']= $toWallet->id;
        $fromAmount->data['second_amount'] = $toAmount->amount;

        $withdrawTransaction = $this->getChangeWalletBalanceCommand()->handle($fromWallet, $fromAmount, false);
        if(!$withdrawTransaction){
            return [];
        }

        $toAmount->data[ 'second_wallet_id'] = $fromWallet->id;
        $toAmount->data['second_amount'] = $fromAmount->amount;
        $toAmount->data['related_to'] = $withdrawTransaction->id;

        $depositTransaction = $this->getChangeWalletBalanceCommand()->handle($toWallet, $toAmount, false);

        if(!$depositTransaction){
            return [];
        }

        $withdrawTransaction->related_to = $depositTransaction->id;
        $withdrawTransaction->save();

        if($transactional){
            DB::commit();
        }
        if($this->dispatchEvent && $withdrawTransaction->isApproved() && $depositTransaction->isApproved()){
            event(new BalanceTransferred($withdrawTransaction, $depositTransaction));
        }
        return [$withdrawTransaction, $depositTransaction];
    }

    /**
     * @return ChangeWalletBalance
     */
    protected function getChangeWalletBalanceCommand(): ChangeWalletBalance
    {
        return app(ChangeWalletBalance::class);
    }
}
