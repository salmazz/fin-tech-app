<?php

namespace App\Services\Wallet\FeeCalculators;

interface FeeCalculatorInterface
{
    public function calculateFee(float $amount): float;
}
