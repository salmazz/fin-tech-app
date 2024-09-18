# FinTech App

## Table of Contents
1. [Introduction](#introduction)
2. [Database Schema](#database-schema)
3. [API Endpoints](#api-endpoints)
    1. [Top Up](#top-up)
    2. [Withdraw](#withdraw)
    3. [Transfer](#transfer)
    4. [Balance](#balance)
    5. [Transactions](#transactions)
4. [Web Routes](#web-routes)
5. [Design Patterns](#design-patterns)
6. [Authentication](#authentication)
7. [Postman Collection](#postman-collection)
8. [Requirements](#requirements)
9. [Getting Started](#getting-started)
10. [Testing](#testing)
11. [Running the Application](#running-the-application)

## Introduction
This project provides a set of APIs for managing a wallet system. Users can top-up, withdraw, and transfer funds between wallets. The API also supports real-time balance updates and transaction tracking.

## Database Schema
### Entities and Attributes:
- **users**: id, name, email, password, email_verified_at
- **wallets**: id, user_id, balance, created_at, updated_at
- **transactions**: id, wallet_id, type (topup, withdraw, transfer, fee), amount, recipient_wallet_id, fee, created_at, updated_at

## API Endpoints

### 1. Top Up
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

### 2. Withdraw
**Endpoint:** `/api/wallet/withdraw`  
**Method:** POST  
**Description:** Withdraw funds from the wallet.

**Request Body:**
```json
{
    "amount": 200
}
```

### 3. Transfer
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

### 4. Balance
**Endpoint:** `/api/wallet/balance`  
**Method:** GET  
**Description:** Retrieve the current balance.

### 5. Transactions
**Endpoint:** `/api/wallet/transactions`  
**Method:** GET  
**Description:** Retrieve the list of all transactions for the authenticated user.


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


## Design Patterns

### 1. **Strategy Design Pattern:**
This pattern is used to calculate fees for transactions like withdrawals and transfers. Depending on the amount, a different fee strategy is applied to ensure a flexible and scalable system.

### Example:
- For amounts greater than $25, a higher fee percentage is applied using the `HighAmountFeeStrategy`.
- For smaller amounts, `LowAmountFeeStrategy` is used.

### 2. **Repository Pattern:**
All data interactions are handled using repositories to ensure loose coupling and better separation of concerns.

## Authentication
Authentication is handled via Laravel Sanctum. You must pass the `Bearer token` in the request headers to access protected routes.

## Postman Collection
A Postman collection is available with all the API endpoints for easy testing and integration. It includes example requests and expected responses.

**Link to Postman Collection:** [Postman Collection](https://elements.getpostman.com/redirect?entityId=6208228-85510af4-b0fd-4767-8106-b61fefd71cd2&entityType=collection)
Please update the environment variables in Postman:
- `app_url`: Your app link
- `token`: Retrieved after login.

## Requirements
- PHP 8.2
- MySQL 5.7+
- Laravel 10.x

## Getting Started
1. Clone this repository:
    ```bash
    git clone https://github.com/salmazz/fin-tech-app.git
    cd fin-tech-app
    ```
2. Set up the environment:
    ```bash
    cp .env.example .env
    composer install
    composer dumpautoload
    ```
3. Set up the database:
    ```bash
    php artisan migrate --seed
    ```
   

## Testing
To run the tests:
```bash
php artisan test
```

### Test Coverage:

#### Web Test Cases:

- **Home Page Test:** Ensures the home page displays the correct user balance and wallet information.
- **Top Up Test:** Verifies that a user can successfully top-up their wallet through the UI.
- **Withdraw Test:** Verifies that a user can withdraw funds from their wallet via the web interface.
- **Transaction History Test:** Ensures that the transaction history page correctly paginates and displays transactions.

#### API Test Cases:

- **Top Up API Test:** Ensures that the `/api/wallet/topup` endpoint works correctly and updates the wallet balance.
- **Withdraw API Test:** Verifies that the `/api/wallet/withdraw` endpoint successfully processes withdrawals.
- **Transfer API Test:** Ensures that the `/api/wallet/transfer` endpoint transfers funds between users.
- **Transaction API Test:** Verifies that the `/api/wallet/transactions` endpoint retrieves the user's transactions with proper pagination.

---

Feel free to adjust any details to better suit your project setup!
