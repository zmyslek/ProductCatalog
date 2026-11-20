<?php

namespace App\Services\Llm;

// LlmClient implementation that calls the OpenAI API over HTTPS/JSON.

class OpenAiClient implements LlmClient
{
    public function generate(string $prompt): string
    {
        throw new \App\Exceptions\LlmUnavailableException('OpenAI is not configured.');
    }
}
?>