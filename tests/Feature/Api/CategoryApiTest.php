<?php

namespace Tests\Feature\Api;

// Feature tests for the category API endpoints.

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class CategoryApiTest extends TestCase
{
    public function test_category_show_includes_active_products(): void
    {
        DB::table('category')->insert(['id' => 1, 'slug' => 'beauty', 'name' => 'Beauty', 'url' => '/beauty']);
        DB::table('product')->insert([
            ['id' => 1, 'title' => 'Active', 'description' => 'Description', 'category' => 1, 'price' => 10, 'discount_percentage' => null, 'image' => 'https://example.test/a.jpg', 'active' => 1],
            ['id' => 2, 'title' => 'Inactive', 'description' => 'Description', 'category' => 1, 'price' => 20, 'discount_percentage' => null, 'image' => 'https://example.test/b.jpg', 'active' => 0],
        ]);

        $this->getJson('/api/categories/1')->assertOk()
            ->assertJsonPath('data.name', 'Beauty')
            ->assertJsonCount(1, 'data.products')
            ->assertJsonPath('data.products.0.title', 'Active');
    }
}