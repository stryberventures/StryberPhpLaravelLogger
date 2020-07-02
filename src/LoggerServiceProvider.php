<?php

declare(strict_types=1);

use Illuminate\Support\ServiceProvider;

final class LoggerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes(
            [
                __DIR__ . '../config/logging.php' => $this->app->configPath('logging.php'),
            ]
        );
    }

    public function register(): void
    {
        $this->app->singleton(LoggerMiddleware::class);
    }
}
