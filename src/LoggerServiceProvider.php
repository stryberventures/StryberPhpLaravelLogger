<?php

declare(strict_types=1);

namespace Stryber\Logger;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

final class LoggerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes(
            [
                __DIR__ . '/../config/stryber-logging.php' => $this->app->configPath('stryber-logging.php'),
                __DIR__ . '/../config/stryber-logging-middleware.php' => $this->app->configPath('stryber-logging-middleware.php'),
            ],
            'stryber-logging'
        );
    }

    public function register(): void
    {
        /** @var Repository $config */
        $config = $this->app['config'];
        $this->mergeConfigs($config);
        $this->registerCollectors($config);
        $this->registerLoggerMiddleware($config);
    }

    private function mergeConfigs(Repository $config): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/stryber-logging.php', 'stryber-logging');
        $this->mergeConfigFrom(__DIR__ . '/../config/stryber-logging-middleware.php', 'stryber-logging-middleware');

        $config->set('logging', array_merge($config->get('logging'), $config->get('stryber-logging')));
    }

    private function registerCollectors(Repository $config): void
    {
        $collectors = $config->get('stryber-logging-middleware.collectors');

        foreach ($collectors as $collector => $collectorConfig) {
            foreach ($collectorConfig as $variableName => $value) {
                $this->app->when($collector)
                    ->needs("\${$variableName}")
                    ->give($value);
            }
        }
    }

    private function registerLoggerMiddleware(Repository $config): void
    {
        /** @var Repository $config */
        $config = $this->app['config'];
        $this->app->when(LoggerMiddleware::class)
            ->needs('$requestCollectors')
            ->give($config->get('stryber-logging-middleware.middleware.collectors.request'));
        $this->app->when(LoggerMiddleware::class)
            ->needs('$responseCollectors')
            ->give($config->get('stryber-logging-middleware.middleware.collectors.response'));
        $this->app->singleton(LoggerMiddleware::class);
        /** @var Router $router */
        $router = $this->app['router'];
        $router->aliasMiddleware('log', LoggerMiddleware::class);
    }
}
