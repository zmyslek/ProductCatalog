<?php

namespace App\Models;

// CartItem model for the `cart_items` table. Links a cart to a product with a quantity, unit price and optional discount.

use App\Support\Price;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $table = 'cart_items';
    public $timestamps = false;

    protected $fillable = ['cart_id', 'product_id', 'quantity', 'unit_price', 'discount_percentage'];

    protected function casts(): array
    {
        return [
            'cart_id' => 'integer',
            'product_id' => 'integer',
            'quantity' => 'integer',
            'unit_price' => 'float',
            'discount_percentage' => 'float',
        ];
    }

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Line total: the price stored on the line (not the product's current price) x quantity, minus the line's discount.
     */
    public function finalPrice(): float
    {
        return Price::applyDiscount($this->unit_price * $this->quantity, $this->discount_percentage);
    }
}
