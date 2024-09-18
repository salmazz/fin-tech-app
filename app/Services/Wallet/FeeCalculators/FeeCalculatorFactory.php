<?php
namespace App\Services\Wallet\FeeCalculators;

class FeeCalculatorFactory
{
    public function getCalculator(float $amount): FeeCalculatorInterface
    {
        if ($amount > 25) {
            return new StandardFeeCalculator();
        }

        return new NoFeeCalculator();
    }
}
