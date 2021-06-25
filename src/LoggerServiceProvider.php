<?php

declare(strict_types=1);

namespace Stryber\Logger;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Log\LogManager;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;
use Stryber\Logger\Processors\UuidMonologProcessor;
use Stryber\Uuid\PersistentUuidGenerator;
use Stryber\Uuid\UuidGenerator;

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
        $this->registerLogger();
        $this->registerCollectors($config);
        $this->registerLoggerMiddleware($config);
        $this->registerProcessors();
    }

    private function mergeConfigs(Repository $config): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/stryber-logging.php', 'stryber-logging');
        $this->mergeConfigFrom(__DIR__ . '/../config/stryber-logging-middleware.php', 'stryber-logging-middleware');
        $config->set(
            'logging',
            array_merge_recursive(
                $config->get('logging'),
                $config->get('stryber-logging')
            )
        );
    }

    private function registerLogger(): void
    {
        /** @var LogManager $log */
        $log = $this->app['log'];
        $this->app->bind(LoggerInterface::class, function () use ($log): Logger {
            return new Logger(
                $log->channel('stdout'),
                $log->channel('stderr')
            );
        });
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

    private function registerProcessors(): void
    {
        $this->app->when(UuidMonologProcessor::class)
            ->needs(UuidGenerator::class)
            ->give(PersistentUuidGenerator::class);
    }
}
