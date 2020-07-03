<?php

use Monolog\Handler\StreamHandler;
use Stryber\Logger\Processors\ProcessorsPusher;
use Stryber\Logger\Taps\FormatterConfigurator;

return [
    'channels' => [
        'stderr' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'tap' => [
                FormatterConfigurator::class,
                ProcessorsPusher::class,
            ],
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'stdout' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'tap' => [
                FormatterConfigurator::class,
                ProcessorsPusher::class,
            ],
            'with' => [
                'stream' => 'php://stdout',
            ],
        ],
    ]
];
