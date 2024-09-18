<?php

namespace App\Services\Wallet;

use App\Common\Enums\Wallet\TransactionTypesEnum;
use App\Exceptions\InsufficientBalanceException;
use App\Exceptions\WalletNotFoundException;
use App\Repositories\Wallet\WalletRepositoryInterface;
use App\Services\Wallet\FeeCalculators\FeeCalculatorFactory;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WalletService
{
    protected $walletRepository;
    protected $feeCalculatorFactory;

    public function __construct(WalletRepositoryInterface $walletRepository,FeeCalculatorFactory $feeCalculatorFactory)
    {
        $this->walletRepository = $walletRepository;
        $this->feeCalculatorFactory = $feeCalculatorFactory;
    }

    public function topUp($userId, $amount)
    {
        return DB::transaction(function () use ($userId, $amount) {
            $wallet = $this->walletRepository->findByUserId($userId);

            if (!$wallet) {
                throw new WalletNotFoundException();
            }

            $this->walletRepository->updateBalance($wallet, $amount);

            $this->walletRepository->saveTransaction([
                'wallet_id' => $wallet->id,
                'amount' => $amount,
                'type' => TransactionTypesEnum::TOP_UP
            ]);

            return $wallet;
        });
    }

    public function withdraw($userId, $amount)
    {
        $wallet = $this->walletRepository->findByUserId($userId);

        if (!$wallet) {
            throw new Exception('Wallet not found');
        }

        if ($wallet->balance < $amount) {
            throw new Exception('Insufficient balance');
        }

        DB::beginTransaction();

        try {
            $this->walletRepository->updateBalance($wallet, -$amount);

            $this->walletRepository->saveTransaction([
                'wallet_id' => $wallet->id,
                'amount' => -$amount,
                'type' =>  TransactionTypesEnum::WITHDRAW
            ]);

            DB::commit();

        } catch (Exception $e) {
            Log::error('Withdrawal failed for user ' . $userId . ': ' . $e->getMessage());
            DB::rollBack();
            throw new Exception('Withdrawal failed: ' . $e->getMessage());
        }

        return $wallet;
    }

    public function transfer($senderUserId, $recipientUserId, $amount)
    {
        $senderWallet = $this->walletRepository->findByUserId($senderUserId);
        $recipientWallet = $this->walletRepository->findByUserId($recipientUserId);

        if (!$senderWallet || !$recipientWallet) {
            throw new Exception('Sender or recipient wallet not found.');
        }

        if ($senderWallet->balance < $amount) {
            throw new InsufficientBalanceException();
        }

        $feeCalculator = $this->feeCalculatorFactory->getCalculator($amount);
        $fee = $feeCalculator->calculateFee($amount);

        $transaction = null;

        DB::transaction(function () use ($senderWallet, $recipientWallet, $amount, $fee, &$transaction) {
            $this->walletRepository->updateBalance($senderWallet, -($amount + $fee));

            $this->walletRepository->updateBalance($recipientWallet, $amount);

            $transaction =$this->walletRepository->saveTransaction([
                'wallet_id' => $senderWallet->id,
                'amount' => -$amount,
                'type' => TransactionTypesEnum::TRANSFER,
                'recipient_wallet_id' => $recipientWallet->id,
                'fee' => $fee
            ]);
        });

        return $transaction;
    }

    public function getBalance($userId)
    {
        $wallet = $this->walletRepository->findByUserId($userId);

        if (!$wallet) {
            throw new WalletNotFoundException();
        }

        return $wallet;
    }

    public function getTransactions($userId)
    {
        $wallet = $this->walletRepository->findByUserId($userId);

        if (!$wallet) {
            throw new WalletNotFoundException();
        }

        return $wallet;
    }

    public function getUserTransactions($userId, $perPage = 10)
    {
        $wallet = $this->walletRepository->findByUserId($userId);

        if (!$wallet) {
            throw new Exception('Wallet not found for user.');
        }

        return $wallet->transactions()->get();
    }
}
