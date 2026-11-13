<?php

namespace App\Models;

// Category model for the `category` table (slug, name, url). A category has many products.

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table = 'category';
    public $timestamps = false;

    protected $fillable = ['slug', 'name', 'url'];

    /**
     * Products in this category (product.category -> category.id).
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category');
    }
}
