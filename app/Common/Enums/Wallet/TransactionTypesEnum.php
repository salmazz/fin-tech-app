<?php
namespace App\Common\Enums\Wallet;
enum TransactionTypesEnum{

    const TRANSFER = 'transfer';

    const TOP_UP = 'topup';

    const WITHDRAW = 'withdraw';
    const FEE =  'fee';
}
