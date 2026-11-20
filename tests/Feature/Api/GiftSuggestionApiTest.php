<?php

namespace Tests\Feature\Api;

// Feature tests for the gift suggestion endpoint, using a fake LLM client.

use Tests\TestCase;
use App\Services\Llm\LlmClient;
use Illuminate\Support\Facades\DB;

class GiftSuggestionApiTest extends TestCase
{
    public function test_suggestions_are_returned_from_the_llm_client(): void
    {
        $this->seedCart();
        $this->app->bind(LlmClient::class, fn () => new class implements LlmClient {
            public function generate(string $prompt): string
            {
                return '{"suggestions":[{"title":"Ribbon","reason":"Complements the gift."},{"title":"Card","reason":"Adds a personal message."},{"title":"Box","reason":"Completes the presentation."}]}';
            }
        });

        $this->getJson('/api/carts/1/gift-suggestions')->assertOk()
            ->assertJsonPath('data.suggestions.0.title', 'Ribbon');
    }

    public function test_provider_failure_returns_service_unavailable(): void
    {
        $this->seedCart();
        $this->app->bind(LlmClient::class, fn () => new class implements LlmClient {
            public function generate(string $prompt): string
            {
                return 'not json';
            }
        });

        $this->getJson('/api/carts/1/gift-suggestions')->assertStatus(503)
            ->assertJsonPath('message', 'Gift suggestions are temporarily unavailable.');
    }

    private function seedCart(): void
    {
        DB::table('user')->insert(['id' => 1, 'name' => 'Test user']);
        DB::table('cart')->insert(['id' => 1, 'user' => 1]);
        DB::table('product')->insert([
            'id' => 1, 'title' => 'Gift', 'description' => 'Description', 'price' => 10,
            'image' => 'https://example.test/gift.jpg', 'active' => 1,
        ]);
        DB::table('cart_items')->insert([
            'id' => 1, 'cart_id' => 1, 'product_id' => 1, 'quantity' => 1,
            'unit_price' => 10, 'discount_percentage' => null,
        ]);
    }
}