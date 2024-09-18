<?php

namespace App\Http\Controllers\Web\Wallet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Wallet\TopUpRequest;
use App\Http\Requests\Wallet\TransferRequest;
use App\Http\Requests\Wallet\WithdrawRequest;
use App\Models\User;
use App\Services\Wallet\WalletService;
use Exception;
use Illuminate\Support\Facades\Log;
use Throwable;

class WalletController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function showTopUpForm()
    {
        return view('auth.wallet.top-up');
    }

    public function handleTopUp(TopUpRequest $request)
    {
        try {
            $wallet = $this->walletService->topUp(auth()->id(), $request->amount);
            return redirect()->route('wallet.topUp.submit')
                ->with('success', 'Top-up successful! Your new balance is ' . $wallet->balance);
        } catch (Throwable $e) {
            Log::error('Top-up failed for user ' . auth()->id() . ': ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Top-up failed. Please try again later.']);
        }
    }

    public function showWithdrawForm()
    {
        return view('auth.wallet.withdraw');
    }

    public function handleWithdraw(WithdrawRequest $request)
    {
        try {
            $wallet = $this->walletService->withdraw(auth()->id(), $request->amount);
            return redirect()->route('wallet.withdraw')
                ->with('success', 'Withdrawal successful! Your new balance is ' . $wallet->balance);
        } catch (Throwable $e) {
            Log::error('Withdrawal failed for user ' . auth()->id() . ': ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Withdrawal failed. Please try again later.']);
        }
    }

    public function showTransferForm()
    {
        $users = User::where('id', '<>', auth()->id())->get();
        return view('auth.wallet.transfer', compact('users'));
    }

    public function handleTransfer(TransferRequest $request)
    {
        try {
            $transaction = $this->walletService->transfer(auth()->id(), $request->recipient_id, $request->amount);
            return redirect()->route('wallet.transfer.submit')
                ->with('success', 'Transfer successful! Your new balance is ' . $transaction->wallet->balance);
        }catch (Throwable $e) {
            Log::error('Transfer failed for user ' . auth()->id() . ': ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Transfer failed. Please try again later.']);
        }
    }

    public function showTransactions()
    {
        try {
            $transactions = $this->walletService->getTransactions(auth()->id())->paginate(10);
            return view('auth.wallet.transactions', ['transactions' => $transactions]);
        } catch (Throwable $e) {
            Log::error('Failed to retrieve transactions for user ' . auth()->id() . ': ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Failed to retrieve transactions. Please try again later.']);
        }
    }
}
