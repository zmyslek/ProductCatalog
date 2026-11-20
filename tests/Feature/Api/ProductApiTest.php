<?php

namespace Tests\Feature\Api;

// Feature tests for the product API endpoints (listing, filtering, product details).

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class ProductApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::table('category')->insert(['id' => 1, 'slug' => 'beauty', 'name' => 'Beauty', 'url' => '/beauty']);
        DB::table('product')->insert([
            ['id' => 1, 'title' => 'Active', 'description' => 'Description', 'category' => 1, 'price' => 10, 'discount_percentage' => 10, 'image' => 'https://example.test/a.jpg', 'active' => 1],
            ['id' => 2, 'title' => 'Inactive', 'description' => 'Description', 'category' => 1, 'price' => 20, 'discount_percentage' => null, 'image' => 'https://example.test/b.jpg', 'active' => 0],
        ]);
    }

    public function test_index_returns_paginated_active_products_only(): void
    {
        $this->getJson('/api/products?per_page=1')->assertOk()
            ->assertJsonPath('data.0.title', 'Active')
            ->assertJsonPath('meta.per_page', 1)
            ->assertJsonCount(1, 'data');
    }

    public function test_show_includes_category_name_and_discounted_price(): void
    {
        $this->getJson('/api/products/1')->assertOk()
            ->assertJsonPath('data.category.name', 'Beauty')
            ->assertJsonPath('data.discounted_price', 9);
    }
}