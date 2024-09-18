<?php

use App\Http\Controllers\Web\Auth\AuthController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\Transactions\TransactionsController;
use App\Http\Controllers\Web\Wallet\WalletController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    return view('landing-page');
})->name('landing-page');

// Authentication Routes
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('/home', HomeController::class)->name('home');
    Route::get('/transactions', TransactionsController::class)->name('transactions');
});

Route::middleware('auth')->name('wallet.')->group(function () {
    Route::get('wallet/topup', [WalletController::class, 'showTopUpForm'])->name('topUp');
    Route::post('wallet/topup', [WalletController::class, 'handleTopUp'])->name('topUp.submit');

    Route::get('wallet/withdraw', [WalletController::class, 'showWithdrawForm'])->name('withdraw');
    Route::post('wallet/withdraw', [WalletController::class, 'handleWithdraw'])->name('withdraw.submit');

    Route::get('wallet/transfer', [WalletController::class, 'showTransferForm'])->name('transfer');
    Route::post('wallet/transfer', [WalletController::class, 'handleTransfer'])->name('transfer.submit');
});
