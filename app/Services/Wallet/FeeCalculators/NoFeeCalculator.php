<?php
namespace App\Services\Wallet\FeeCalculators;

class NoFeeCalculator implements FeeCalculatorInterface
{
    public function calculateFee(float $amount): float
    {
        return 0;
    }
}
