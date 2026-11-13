<?php

namespace App\Models;

// Cart model for the `cart` table. Belongs to a user and has many cart items.

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    /**
     * Laravel would guess the plural "carts", but the provided database uses singular table names.
     */
    protected $table = 'cart';

    /**
     * The table has no created_at / updated_at columns, so Eloquent must not try to fill them.
     */
    public $timestamps = false;

    /**
     * Columns that may be set through create() / update() from validated API input.
     */
    protected $fillable = ['user'];

    /**
     * Cast columns to proper PHP types (SQLite returns everything as strings or ints).
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'user' => 'integer',
        ];
    }

    /**
     * The line items in this cart (cart_items.cart_id -> cart.id).
     *
     * The foreign key is passed explicitly so the relationship doesn't depend on Laravel's naming guesses.
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class, 'cart_id');
    }

    /**
     * The user who owns this cart (cart.user -> user.id).
     * Not called user() because that would clash with the `user` column.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user');
    }

    /**
     * Sum of all line totals after discounts. Load 'items' first to avoid extra queries.
     */
    public function total(): float
    {
        return round($this->items->sum(fn (CartItem $item) => $item->finalPrice()), 2);
    }
}