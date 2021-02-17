<?php


namespace Ashrafi\WalletManager\Commands;


use Ashrafi\WalletManager\Facades\WalletModel;
use Ashrafi\WalletManager\Facades\WalletTransactionModel;
use Ashrafi\WalletManager\Models\TransactionType;
use Ashrafi\WalletManager\Models\Wallet;
use Ashrafi\WalletManager\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class CreateWalletTransaction extends Command
{
    /**
     * @param Wallet $wallet
     * @param TransactionType $transactionType
     * @param array $transactionData
     * @param bool $transactional
     * @return WalletTransaction|null
     */
    public function handle(Wallet $wallet, TransactionType $transactionType, array $transactionData, bool $transactional = true)
    {
        if($transactional){
            DB::beginTransaction();
        }

        $walletTransaction = WalletTransactionModel::create([
            'wallet_id' => $wallet->id,
            'user_id' => $wallet->user_id,
            'account_id' => $wallet->account_id,
            'transaction_type_id' => $transactionType->id,
            'amount' => $transactionData['amount'],
            'reference_type' => $transactionData['reference_type'] ?? null,
            'reference_id' => $transactionData['reference_id'] ?? null,
            'related_to' => $transactionData['related_to'] ?? null,
            'status'=>$transactionData['status'],
            'payload'=>$transactionData+['prev_balance'=>$wallet->balance],
        ]);

        if($walletTransaction){
            $this->updateBalance($wallet, $walletTransaction);
        }
        if($transactional){
            DB::commit();
        }
        return $walletTransaction;
    }

    /**
     * @param Wallet $wallet
     * @param WalletTransaction|null $walletTransaction
     */
    protected function updateBalance(Wallet $wallet, WalletTransaction $walletTransaction)
    {

        if($walletTransaction->amount < 0 && !$walletTransaction->isApproved()){
            WalletModel::query()->where('id', $walletTransaction->wallet_id)->update(['freeze_balance' => DB::raw('freeze_balance + '.abs($walletTransaction->amount))]);
        }

        $newWallet = $walletTransaction->recalculateBalance();
        $wallet->balance = $newWallet->balance;
    }
}
