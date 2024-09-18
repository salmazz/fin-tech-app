<?php


namespace App\Repositories\Wallet;

use App\Models\Wallet;
use App\Models\Transaction;

class WalletRepository implements WalletRepositoryInterface
{
    public function findByUserId($userId)
    {
        return Wallet::with('transactions')->where('user_id', $userId)->first();
    }

    public function updateBalance(Wallet $wallet, $amount): bool
    {
        $wallet->balance += $amount;
        return $wallet->save();
    }

    public function saveTransaction(array $transactionData)
    {
        return Transaction::create($transactionData);
    }

    public function transfer(Wallet $senderWallet, Wallet $recipientWallet, $amount): bool
    {
        if ($senderWallet->balance < $amount) {
            return false;
        }

        $fee = 0;
        if ($amount > 25) {
            $fee = 2.5 + ($amount * 0.10);
        }

        $senderWallet->balance -= ($amount + $fee);
        $senderWallet->save();

        $recipientWallet->balance += $amount;
        $recipientWallet->save();

        Transaction::create([
            'wallet_id' => $senderWallet->id,
            'amount' => -$amount,
            'type' => 'transfer',
            'recipient_wallet_id' => $recipientWallet->id,
            'fee' => $fee
        ]);

        return true;
    }
}
