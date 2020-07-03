<?php

declare(strict_types=1);

namespace Stryber\Logger;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\ServiceProvider;

final class LoggerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes(
            [
                __DIR__ . '/../config/stryber-logging.php' => $this->app->configPath('stryber-logging.php'),
                __DIR__ . '/../config/stryber-logging-middleware.php' => $this->app->configPath('stryber-logging-middleware.php'),
            ]
        );
    }

    public function register(): void
    {
        $this->mergeConfigs();
        $this->registerLoggerMiddleware();
    }

    private function mergeConfigs(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/stryber-logging.php', 'stryber-logging');
        /** @var Repository $config */
        $config = $this->app['config'];
        $config->set('logging', array_merge($config->get('logging'), $config->get('stryber-logging')));
    }

    private function registerLoggerMiddleware(): void
    {
        /** @var Repository $config */
        $config = $this->app['config'];
        $paramsConfig = [
            '$ignoreHeaders' => 'stryber-logging-middleware.ignore_headers',
            '$ignoreRequestParams' => 'stryber-logging-middleware.ignore_request_params',
            '$ignoreResponseParams' => 'stryber-logging-middleware.ignore_response_params'
        ];

        foreach ($paramsConfig as $param => $configPath) {
            $this->app->when(LoggerMiddleware::class)
                ->needs($param)
                ->give($config->get($configPath));
        }
    }
}
