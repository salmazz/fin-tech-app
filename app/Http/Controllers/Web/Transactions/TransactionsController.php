<?php

namespace App\Http\Controllers\Web\Transactions;

use App\Http\Controllers\Controller;
use App\Services\Wallet\WalletService;
use Exception;
use Illuminate\Support\Facades\Log;

class TransactionsController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }


    public function __invoke()
    {try {
            $transactions = $this->walletService->getUserTransactions(auth()->id());

            return view('auth.transactions.index', compact('transactions'));
        } catch (Exception $e) {
            Log::error('Failed to retrieve transactions: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to retrieve transactions. Please try again later.']);
        }
    }
}
