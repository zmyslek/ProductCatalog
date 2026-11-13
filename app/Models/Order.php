<?php

namespace App\Models;

// Order model for the `order` table. Belongs to a user and has many order items.

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    // `order` is an SQL keyword; Laravel quotes table names, so this is safe.
    protected $table = 'order';
    public $timestamps = false;

    protected $fillable = ['user'];

    protected function casts(): array
    {
        return [
            'user' => 'integer',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    /**
     * The user who placed the order (order.user -> user.id).
     * Not called user() because that would clash with the `user` column.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user');
    }

    /**
     * Sum of all line totals after discounts. Load 'items' first to avoid extra queries.
     */
    public function total(): float
    {
        return round($this->items->sum(fn (OrderItem $item) => $item->finalPrice()), 2);
    }
}
