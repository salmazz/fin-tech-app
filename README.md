# FinTech App

## Table of Contents
1. [Introduction](#introduction)
2. [Database Schema](#database-schema)
3. [Web Routes](#web-routes)
4. [API Endpoints](#api-endpoints)
    1. [Top Up](#top-up)
    2. [Withdraw](#withdraw)
    3. [Transfer](#transfer)
    4. [Balance](#balance)
    5. [Transactions](#transactions)
5. [Design Patterns](#design-patterns)
6. [Authentication](#authentication)
7. [Postman Collection](#postman-collection)
8. [Requirements](#requirements)
9. [Getting Started](#getting-started)
10. [Testing](#testing)
11. [Running the Application](#running-the-application)

## Introduction
This project provides a set of APIs and web views for managing a wallet system. Users can top-up, withdraw, transfer funds between wallets, and view transaction history. The API supports real-time balance updates and transaction tracking.

---

## Database Schema

### Entities and Attributes:
- **users**: id, name, email, password, email_verified_at
- **wallets**: id, user_id, balance, created_at, updated_at
- **transactions**: id, wallet_id, type (topup, withdraw, transfer, fee), amount, recipient_wallet_id, fee, created_at, updated_at

---

## Web Routes

These routes define the user-facing pages for managing wallet functionalities through the UI.

### 1. **Landing Page**
**Endpoint:** `/`  
**Method:** GET  
**Description:** Displays the landing page.  
**Route Name:** `landing-page`

### 2. **Login Page**
**Endpoint:** `/login`  
**Method:** GET  
**Description:** Displays the login form.  
**Route Name:** `login`

### 3. **Registration Page**
**Endpoint:** `/register`  
**Method:** GET  
**Description:** Displays the registration form.  
**Route Name:** `register`

### 4. **Home Page**
**Endpoint:** `/home`  
**Method:** GET  
**Description:** Displays the home page with user’s balance and links to wallet functionalities.  
**Route Name:** `home`

### 5. **Top Up Page**
**Endpoint:** `/wallet/topup`  
**Method:** GET  
**Description:** Displays the form to top-up the user’s wallet.  
**Route Name:** `wallet.topUp`

### 6. **Withdraw Page**
**Endpoint:** `/wallet/withdraw`  
**Method:** GET  
**Description:** Displays the form to withdraw from the user’s wallet.  
**Route Name:** `wallet.withdraw`

### 7. **Transfer Page**
**Endpoint:** `/wallet/transfer`  
**Method:** GET  
**Description:** Displays the form to transfer funds to another user.  
**Route Name:** `wallet.transfer`

### 8. **Transaction History Page**
**Endpoint:** `/transactions`  
**Method:** GET  
**Description:** Displays the user’s transaction history.  
**Route Name:** `transactions`

---

## API Endpoints

These routes are used to interact with the wallet system via APIs. The endpoints are protected with Laravel Sanctum for authentication.

### 1. **Top Up**
**Endpoint:** `/api/wallet/topup`  
**Method:** POST  
**Description:** Add funds to the wallet.

**Headers:**
- Content-Type: "application/json"
- Accept: "application/json"
- Authorization: "Bearer {{token}}"

**Request Body:**
```json
{
    "amount": 500
}
```

**Response Example:**
```json
{
    "status": "success",
    "message": "Top-up successful",
    "data": {
        "balance": {
            "wallet_number": 1,
            "balance": "500.00",
            "user": {
                "name": "John Doe",
                "email": "john@example.com"
            }
        }
    }
}
```

### 2. **Withdraw**
**Endpoint:** `/api/wallet/withdraw`  
**Method:** POST  
**Description:** Withdraw funds from the wallet.

**Request Body:**
```json
{
    "amount": 200
}
```

### 3. **Transfer**
**Endpoint:** `/api/wallet/transfer`  
**Method:** POST  
**Description:** Transfer funds to another user's wallet.

**Request Body:**
```json
{
    "recipient_id": 2,
    "amount": 100
}
```

### 4. **Balance**
**Endpoint:** `/api/wallet/balance`  
**Method:** GET  
**Description:** Retrieve the current balance.

### 5. **Transactions**
**Endpoint:** `/api/wallet/transactions`  
**Method:** GET  
**Description:** Retrieve the list of all transactions for the authenticated user.

---

## Testing

To ensure the functionality of both the **UI web app** and **API routes**, the following test cases are implemented:

### Web Test Cases:

- **Home Page Test:** Ensures the home page displays the correct user balance and wallet information.
- **Top Up Test:** Verifies that a user can successfully top-up their wallet through the UI.
- **Withdraw Test:** Verifies that a user can withdraw funds from their wallet via the web interface.
- **Transaction History Test:** Ensures that the transaction history page correctly paginates and displays transactions.

### API Test Cases:

- **Top Up API Test:** Ensures that the `/api/wallet/topup` endpoint works correctly and updates the wallet balance.
- **Withdraw API Test:** Verifies that the `/api/wallet/withdraw` endpoint successfully processes withdrawals.
- **Transfer API Test:** Ensures that the `/api/wallet/transfer` endpoint transfers funds between users.
- **Transaction API Test:** Verifies that the `/api/wallet/transactions` endpoint retrieves the user's transactions with proper pagination.

---

## Routes File Structure

### Web Routes (`routes/web.php`)
```php
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
```

### API Routes (`routes/api.php`)
```php
Route::group(['name' => 'auth.'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::group(['middleware' => 'auth:sanctum'], function() {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
    });
});

Route::middleware('auth:sanctum')->name('wallet.')->prefix('wallet')->group(function () {
    Route::post('/topup', [WalletController::class, 'topUp']);
    Route::post('/withdraw', [WalletController::class, 'withdraw']);
    Route::post('/transfer', [WalletController::class, 'transfer']);
    Route::get('/balance', [WalletController::class, 'balance']);
    Route::get('/transactions', [WalletController::class, 'transactions']);
});
```
