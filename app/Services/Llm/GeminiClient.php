<?php

namespace App\Services\Llm;

// LlmClient implementation that calls the Google Gemini API over HTTPS/JSON.

use App\Exceptions\LlmUnavailableException;
use Gemini\Laravel\Facades\Gemini;
use Gemini\Data\GenerationConfig;
use Gemini\Enums\ResponseMimeType;
use Illuminate\Support\Facades\Log;

class GeminiClient implements LlmClient
{
    public function generate(string $prompt): string
    {
        $apiKey = config('gemini.api_key');
        if (!is_string($apiKey) || trim($apiKey) === '') {
            throw new LlmUnavailableException('Gemini is not configured.');
        }

        $models = ['gemini-3.5-flash', 'gemini-3.6-flash', 'gemini-2.5-flash-lite', 'gemini-flash-lite-latest', 'gemini-3.8-flash'];
        $lastError = null;

        foreach ($models as $model) {
            try {
                $generationConfig = new GenerationConfig(
                    maxOutputTokens: 500,
                    temperature: 0.35,
                    responseMimeType: ResponseMimeType::APPLICATION_JSON,
                );
                $response = Gemini::generativeModel(model: $model)
                    ->withGenerationConfig($generationConfig)
                    ->generateContent($prompt);
                $text = $response->text();
                if (is_string($text) && trim($text) !== '') {
                    return $text;
                }
            } catch (\Throwable $exception) {
                $lastError = $exception;
                Log::warning('Gemini model request failed', [
                    'model' => $model,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        throw new LlmUnavailableException(
            $lastError ? 'The gift provider could not be reached: '.$lastError->getMessage() : 'The gift provider returned an empty response.'
        );
    }
}
?>