<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Feature tests don't need the compiled frontend bundle.
        $this->withoutVite();

        foreach (['order_items', 'order', 'cart_items', 'cart', 'product', 'category', 'user'] as $table) {
            Schema::dropIfExists($table);
        }

        Schema::create('user', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('name');
            $table->boolean('deleted')->default(false);
        });
        Schema::create('category', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('slug');
            $table->string('name');
            $table->string('url');
        });
        Schema::create('product', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('title');
            $table->string('description');
            $table->integer('category')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('discount_percentage', 10, 2)->nullable();
            $table->decimal('rating', 10, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->string('brand')->nullable();
            $table->string('sku')->nullable();
            $table->integer('weight')->default(0);
            $table->string('warrantyInformation')->nullable();
            $table->string('shippingInformation')->nullable();
            $table->string('returnPolicy')->nullable();
            $table->integer('minimumOrderQuantity')->nullable();
            $table->string('image');
            $table->boolean('active')->default(false);
        });
        Schema::create('cart', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('user');
        });
        Schema::create('cart_items', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('cart_id');
            $table->integer('product_id');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('discount_percentage', 10, 2)->nullable();
        });
        Schema::create('order', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('user');
        });
        Schema::create('order_items', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('order_id');
            $table->integer('product_id');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('discount_percentage', 10, 2)->nullable();
        });
    }
}
