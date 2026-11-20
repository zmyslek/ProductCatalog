<?php

namespace App\Services;

// Builds a prompt from a cart's items, asks the LLM client for gift ideas and parses the response into suggestions.

use App\Services\Llm\LlmClient;

class GiftSuggestionService
{
    public function __construct(private LlmClient $llm)
    {
    }

    public function suggest(\App\Models\Cart $cart): array
    {
        $cart->load(['items.product.productCategory']);
        $items = $cart->items->map(function ($item) {
            return [
                'title' => $item->product?->title ?? 'Unknown product',
                'category' => $item->product?->productCategory?->name,
                'quantity' => $item->quantity,
            ];
        })->all();

        $prompt = "Recommend exactly 3 genuinely complementary gifts for this shopping cart.\n".
            "Do not simply repeat cart categories or recommend another item of the same type.\n".
            "Prefer products that fit the occasion, recipient, and items already chosen.\n".
            "Cart items: ".json_encode($items, JSON_UNESCAPED_SLASHES)."\n".
            'Return only valid JSON: '.
            '{"suggestions":[{"title":"concise gift name","reason":"specific one-sentence reason"}]}';

        $text = $this->llm->generate($prompt);
        $jsonText = trim($text);
        if (str_starts_with($jsonText, '```')) {
            $jsonText = preg_replace('/^```(?:json)?\s*/i', '', $jsonText) ?? $jsonText;
            $jsonText = preg_replace('/\s*```$/', '', $jsonText) ?? $jsonText;
        }
        $decoded = json_decode($jsonText, true);

        if (!is_array($decoded) || !isset($decoded['suggestions']) || !is_array($decoded['suggestions'])) {
            throw new \App\Exceptions\LlmUnavailableException('The gift provider returned invalid suggestions.');
        }

        return array_values(array_filter(array_map(function ($suggestion) {
            if (!is_array($suggestion)) {
                return null;
            }
            $title = trim((string) ($suggestion['title'] ?? ''));
            $reason = trim((string) ($suggestion['reason'] ?? ''));
            return $title !== '' ? ['title' => $title, 'reason' => $reason] : null;
        }, $decoded['suggestions'])));
    }
}
?>