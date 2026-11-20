<?php

namespace Tests\Feature\Api;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CartApiTest extends TestCase
{
    public function test_show_returns_product_name_discounted_line_price_and_total(): void
    {
        DB::table('user')->insert(['id' => 1, 'name' => 'Test user']);
        DB::table('cart')->insert(['id' => 1, 'user' => 1]);
        DB::table('product')->insert([
            'id' => 1, 'title' => 'Gift', 'description' => 'Description', 'price' => 10,
            'image' => 'https://example.test/gift.jpg', 'active' => 1,
        ]);
        DB::table('cart_items')->insert([
            'id' => 1, 'cart_id' => 1, 'product_id' => 1, 'quantity' => 2,
            'unit_price' => 10, 'discount_percentage' => 10,
        ]);

        $this->getJson('/api/carts/1')->assertOk()
            ->assertJsonPath('data.items.0.product_name', 'Gift')
            ->assertJsonPath('data.items.0.final_price', 18)
            ->assertJsonPath('data.total', 18);
    }

    public function test_missing_cart_returns_not_found(): void
    {
        $this->getJson('/api/carts/999')->assertNotFound();
    }
}
