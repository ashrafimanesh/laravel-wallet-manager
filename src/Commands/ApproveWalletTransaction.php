<?php


namespace Ashrafi\WalletManager\Commands;


use Ashrafi\WalletManager\Contracts\iUser as User;
use Ashrafi\WalletManager\Events\WalletTransactionApproved;
use Ashrafi\WalletManager\Facades\WalletModel;
use Ashrafi\WalletManager\Facades\WalletTransactionLogModel;
use Ashrafi\WalletManager\Models\WalletTransaction;
use Ashrafi\WalletManager\Models\WalletTransactionLog;
use Illuminate\Support\Facades\DB;

class ApproveWalletTransaction extends Command
{
    public function handle(WalletTransaction $walletTransaction, User $doneBy, $transactional = true)
    {

        if($transactional){
            DB::beginTransaction();
        }

        if(!$walletTransaction->isApproved()){
            $this->approve($walletTransaction, $doneBy);
        }
        if($walletTransaction->related_to && $walletTransaction->related && !($walletTransaction->related->isApproved())){
            $this->approve($walletTransaction->related, $doneBy);
        }


        if($transactional){
            DB::commit();
        }
        if($this->dispatchEvent){
            event(new WalletTransactionApproved($walletTransaction));
        }

        return true;
    }

    /**
     * @param WalletTransaction $walletTransaction
     * @param User $doneBy
     * @return WalletTransactionLog|null
     */
    protected function approve(WalletTransaction $walletTransaction, User $doneBy)
    {
        $oldStatus = $walletTransaction->status;
        $walletTransaction->status = WalletTransaction::STATUS_APPROVED;
        $walletTransaction->save();

        if ($walletTransaction->reference && method_exists($walletTransaction->reference, 'done')) {
            $walletTransaction->reference->done($doneBy);
        }

        /** @var WalletTransactionLog $walletTransactionLog */
        $walletTransactionLog = WalletTransactionLogModel::create([
            'wallet_transaction_id' => $walletTransaction->id,
            'field' => 'status',
            'from_value' => $oldStatus,
            'to_value' => $walletTransaction->status,
            'done_by' => $doneBy->id
        ]);

        if($walletTransaction->amount < 0){
            $oldFreeze = $walletTransaction->wallet->freeze_balance;
            WalletModel::query()->where('id', $walletTransaction->wallet_id)->update(['freeze_balance' => DB::raw('freeze_balance - '.abs($walletTransaction->amount))]);
            $wallet = WalletModel::find($walletTransaction->wallet_id);

            /** @var WalletTransactionLog $walletTransactionLog */
            WalletTransactionLogModel::create([
                'wallet_transaction_id' => $walletTransaction->id,
                'field' => 'wallet.freeze_balance',
                'from_value' => $oldFreeze,
                'to_value' => $wallet->freeze_balance,
                'done_by' => $doneBy->id
            ]);

        }
        $walletTransaction->recalculateBalance();

        return $walletTransactionLog;
    }
}
