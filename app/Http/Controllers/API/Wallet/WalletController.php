<?php

namespace App\Http\Controllers\API\Wallet;

use App\Common\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Wallet\TopUpRequest;
use App\Http\Requests\Wallet\TransferRequest;
use App\Http\Requests\Wallet\WithdrawRequest;
use App\Http\Resources\Wallet\TransactionResource;
use App\Http\Resources\Wallet\WalletResource;
use App\Services\Wallet\WalletService;
use Exception;
use Illuminate\Http\JsonResponse;

class WalletController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function topUp(TopUpRequest $request): JsonResponse
    {
        try {
            $wallet = $this->walletService->topUp(auth()->id(), $request->amount);
            return ResponseHelper::success('Top-up successful', ['balance' => new WalletResource($wallet)]);
        } catch (Exception $e) {
            return ResponseHelper::error('Top-up failed', 400, ['exception' => $e->getMessage()]);
        }
    }

    public function withdraw(WithdrawRequest $request): JsonResponse
    {
        try {
            $wallet = $this->walletService->withdraw(auth()->id(), $request->amount);
            return ResponseHelper::success('Withdrawal successful', ['balance' => new WalletResource($wallet)]);
        } catch (Exception $e) {
            return ResponseHelper::error('Withdrawal failed', 400, ['exception' => $e->getMessage()]);
        }
    }

    public function transfer(TransferRequest $request): JsonResponse
    {
        try {
            $transaction = $this->walletService->transfer(auth()->id(), $request->recipient_id, $request->amount);
            return ResponseHelper::success('Transfer successful', new TransactionResource($transaction));
        } catch (Exception $e) {
            return ResponseHelper::error('Transfer failed', 400, ['exception' => $e->getMessage()]);
        }
    }

    public function balance(): JsonResponse
    {
        try {
            $wallet = $this->walletService->getBalance(auth()->id());
            return ResponseHelper::success('Balance retrieved', new WalletResource($wallet));
        } catch (Exception $e) {
            return ResponseHelper::error('Failed to retrieve balance', 400, ['exception' => $e->getMessage()]);
        }
    }

    public function transactions(): JsonResponse
    {
        try {
            $wallet = $this->walletService->getTransactions(auth()->id());
            $transactions = $wallet->transactions()->paginate(10);

            return ResponseHelper::success('Transactions retrieved', TransactionResource::collection($transactions)->response()->getData(true));
        } catch (Exception $e) {
            return ResponseHelper::error('Failed to retrieve transactions', 400, ['exception' => $e->getMessage()]);
        }
    }
}
