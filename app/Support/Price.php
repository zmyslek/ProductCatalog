<?php

namespace App\Support;

// Single place for the discount calculation, so products, cart items and order items all price the same way.

final class Price
{
    /**
     * Apply a percentage discount to an amount and round to cents.
     * A null discount (allowed by the database) means no discount.
     */
    public static function applyDiscount(float $amount, ?float $discountPercentage): float
    {
        return round($amount * (1 - ($discountPercentage ?? 0) / 100), 2);
    }
}
