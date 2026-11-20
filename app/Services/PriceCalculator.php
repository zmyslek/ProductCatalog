<?php

namespace App\Services;

use App\Support\Price;

final class PriceCalculator
{
    public function discounted(float $amount, ?float $discountPercentage): float
    {
        return Price::applyDiscount($amount, $discountPercentage);
    }

    public function lineTotal(float $unitPrice, int $quantity, ?float $discountPercentage): float
    {
        return $this->discounted($unitPrice * $quantity, $discountPercentage);
    }
}