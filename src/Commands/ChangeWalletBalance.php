<?php


namespace Ashrafi\WalletManager\Commands;


use Ashrafi\WalletManager\Contracts\iRelatedTransactionTypeValidate;
use Ashrafi\WalletManager\Events\WalletBalanceChanged;
use Ashrafi\WalletManager\Exceptions\CommandException;
use Ashrafi\WalletManager\Exceptions\NoEnoughBalanceException;
use Ashrafi\WalletManager\Exceptions\TransactionValidatorException;
use Ashrafi\WalletManager\Facades\TransactionTypeModel;
use Ashrafi\WalletManager\Models\TransactionType;
use Ashrafi\WalletManager\Models\WalletTransaction;
use Ashrafi\WalletManager\ValueObjects\TransactionAmount;
use Ashrafi\WalletManager\Models\Wallet;
use Illuminate\Support\Facades\DB;

class ChangeWalletBalance extends WalletCommand
{

    /**
     * @param Wallet $wallet
     * @param TransactionAmount $transactionAmount
     * @param bool $transactional
     * @return WalletTransaction
     * @throws CommandException
     * @throws TransactionValidatorException
     */
    public function handle(Wallet $wallet, TransactionAmount $transactionAmount, $transactional = true)
    {

        $transactionData = $this->makeTransactionData($transactionAmount, $wallet);

        $wallet = $wallet->recalculateBalance();

        $transactionType = $this->validate($wallet, $transactionAmount, $transactionData);

        if ($transactional) {
            DB::beginTransaction();
        }

        $oldBalance = $wallet->balance;

        $reference = $this->createReferenceRecord($transactionType, $transactionData);

        $transactionData['reference_type'] = $reference ? get_class($reference) : null;
        $transactionData['reference_id'] = $reference ? $reference->id : null;

        /** @var CreateWalletTransaction $command */
        $command = app(CreateWalletTransaction::class);

        $walletTransaction = $command->handle($wallet, $transactionType, $transactionData , false);

        if ($transactional && $walletTransaction) {
            DB::commit();
        }
        if($walletTransaction && $walletTransaction->isApproved()){
            $this->dispatchEvent(new WalletBalanceChanged($wallet, $oldBalance));
        }
        return $walletTransaction;
    }

    /**
     * @param Wallet $wallet
     * @param TransactionAmount $transactionAmount
     * @param array $transactionData
     * @return TransactionType|null
     * @throws CommandException
     * @throws NoEnoughBalanceException
     */
    protected function validate(Wallet $wallet, TransactionAmount $transactionAmount, array $transactionData)
    {
        if (!$transactionAmount->type) {
            throw new CommandException('Undefined transaction type!');
        }
        if (!$transactionAmount->status) {
            throw new CommandException('Undefined transaction status!');
        }

        if($transactionAmount->amount < 0){
            if($wallet->credit - abs($transactionAmount->amount) <= config('wallet_manager.wallet.minimum_balance', 0)){
                throw new NoEnoughBalanceException();
            }
        }

        $transactionType = TransactionTypeModel::active()->whereType($transactionAmount->type)->first();
        if (!($transactionType instanceof TransactionType)) {
            throw new CommandException('Invalid transaction type!');
        }

        $validator = $transactionType->getValidator();
        if ($validator && ($validator instanceof iRelatedTransactionTypeValidate)) {
            $validator->validateRecord($transactionData);
        }

        return $transactionType;
    }

    /**
     * @param TransactionType|null $transactionType
     * @param array $transactionData
     * @return mixed
     */
    protected function createReferenceRecord(TransactionType $transactionType, array $transactionData)
    {
        return $transactionType->createReference($transactionData);
    }

    /**
     * @param TransactionAmount $transactionAmount
     * @param Wallet $wallet
     * @return array
     */
    protected function makeTransactionData(TransactionAmount $transactionAmount, Wallet $wallet): array
    {
        $amount = $transactionAmount->amount * 1;
        return ['user_id' => $wallet->user_id, 'amount' => $amount, 'wallet_id' => $wallet->id, 'status' => $transactionAmount->status] + ($transactionAmount->data ?? []);
    }

}
