<?php

namespace App\Models;

// OrderItem model for the `order_items` table.

use App\Support\Price;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $table = 'order_items';
    public $timestamps = false;

    protected $fillable = ['order_id', 'product_id', 'quantity', 'unit_price', 'discount_percentage'];

    protected function casts(): array
    {
        return [
            'order_id' => 'integer',
            'product_id' => 'integer',
            'quantity' => 'integer',
            'unit_price' => 'float',
            'discount_percentage' => 'float',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Line total: the price stored on the line x quantity, minus the line's discount.
     */
    public function finalPrice(): float
    {
        return Price::applyDiscount($this->unit_price * $this->quantity, $this->discount_percentage);
    }
}
