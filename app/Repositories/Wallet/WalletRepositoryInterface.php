<?php


namespace App\Repositories\Wallet;

use App\Models\Wallet;

interface WalletRepositoryInterface
{
    public function findByUserId($userId);

    public function updateBalance(Wallet $wallet, $amount): bool;

    public function saveTransaction(array $transactionData);

    public function transfer(Wallet $senderWallet, Wallet $recipientWallet, $amount): bool;

}
