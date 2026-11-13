<?php

namespace App\Models;

// Product model for the `product` table: title, price, discount, stock, brand, image and so on. Belongs to a category.

use Illuminate\Database\Eloquent\Model;
use App\Support\Price;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $table = 'product';
    public $timestamps = false;

    /**
     * Columns that may be set through create() / update() from validated API input.
     */
    protected $fillable = [
        'title', 'description', 'category', 'price', 'discount_percentage', 'rating', 'stock',
        'brand', 'sku', 'weight', 'warrantyInformation', 'shippingInformation', 'returnPolicy',
        'minimumOrderQuantity', 'image', 'active',
    ];

    protected function casts(): array
    {
        return [
            'category' => 'integer',
            'price' => 'float',
            'discount_percentage' => 'float',
            'rating' => 'float',
            'stock' => 'integer',
            'weight' => 'integer',
            'minimumOrderQuantity' => 'integer',
            'active' => 'boolean',
        ];
    }

    /**
     * The category this product belongs to (product.category -> category.id).
     *
     * Not called category() because the foreign key column is already named `category`:
     * $product->category would return the integer column, not the relationship.
     */
    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category');
    }

    /**
     * Order lines referencing this product (used to protect order history on delete).
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    /**
     * Current selling price after the product's own discount.
     */
    public function discountedPrice(): float
    {
        return Price::applyDiscount($this->price, $this->discount_percentage);
    }
}
