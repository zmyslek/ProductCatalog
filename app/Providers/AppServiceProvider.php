<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Llm\LlmClient;
use App\Services\Llm\GeminiClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LlmClient::class, GeminiClient::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
