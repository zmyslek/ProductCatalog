<?php

namespace Tests\Unit;

use App\Exceptions\LlmUnavailableException;
use App\Models\Cart;
use App\Services\GiftSuggestionService;
use App\Services\Llm\LlmClient;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class GiftSuggestionServiceTest extends TestCase
{
    public function test_it_builds_a_cart_prompt_and_returns_three_suggestions(): void
    {
        DB::table('user')->insert(['id' => 1, 'name' => 'Test user']);
        DB::table('cart')->insert(['id' => 1, 'user' => 1]);
        DB::table('product')->insert([
            'id' => 1, 'title' => 'Tea set', 'description' => 'Description', 'price' => 10,
            'image' => 'https://example.test/tea.jpg', 'active' => 1,
        ]);
        DB::table('cart_items')->insert([
            'id' => 1, 'cart_id' => 1, 'product_id' => 1, 'quantity' => 1,
            'unit_price' => 10,
        ]);

        $client = new class implements LlmClient {
            public string $prompt = '';

            public function generate(string $prompt): string
            {
                $this->prompt = $prompt;

                return '{"suggestions":[{"title":"Tea towel","reason":"Useful with the tea set."},{"title":"Honey","reason":"Pairs well with tea."},{"title":"Gift card","reason":"Adds a personal touch."},{"title":"Extra","reason":"Must be excluded."}]}';
            }
        };

        $suggestions = (new GiftSuggestionService($client))->suggest(Cart::findOrFail(1));

        self::assertCount(3, $suggestions);
        self::assertSame('Tea towel', $suggestions[0]['title']);
        self::assertStringContainsString('Tea set', $client->prompt);
    }

    public function test_invalid_json_raises_an_unavailable_exception(): void
    {
        $client = new class implements LlmClient {
            public function generate(string $prompt): string
            {
                return 'invalid';
            }
        };

        $this->expectException(LlmUnavailableException::class);
        (new GiftSuggestionService($client))->suggest(new Cart(['id' => 1]));
    }
}