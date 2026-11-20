<?php

namespace Tests\Unit;

// Unit tests for PriceCalculator (discounts, rounding, missing discounts, totals).

use PHPUnit\Framework\TestCase;
use App\Services\PriceCalculator;

class PriceCalculatorTest extends TestCase
{
    public function test_discounted_amount_is_rounded_to_cents(): void
    {
        $calculator = new PriceCalculator();

        self::assertSame(90.0, $calculator->discounted(100, 10));
        self::assertSame(12.35, $calculator->discounted(12.345, null));
    }

    public function test_line_total_applies_discount_after_multiplication(): void
    {
        self::assertSame(8.99, (new PriceCalculator())->lineTotal(5.49, 2, 18.09));
    }
}