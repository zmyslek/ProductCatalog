<?php

namespace App\Models;

// User model for the `user` table (id, name, deleted). Plain model: the API has no login, so it doesn't extend Authenticatable.

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    protected $table = 'user';
    public $timestamps = false;

    protected $fillable = ['name', 'deleted'];

    protected function casts(): array
    {
        return [
            'deleted' => 'boolean',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'user');
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class, 'user');
    }
}
