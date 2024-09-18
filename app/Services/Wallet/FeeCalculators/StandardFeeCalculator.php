<?php
namespace App\Services\Wallet\FeeCalculators;

class StandardFeeCalculator implements FeeCalculatorInterface
{
    public function calculateFee(float $amount): float
    {
        return 2.5 + ($amount * 0.10);
    }
}
