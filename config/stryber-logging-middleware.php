<?php

use Stryber\Logger\Collectors\Request\DataCollector;
use Stryber\Logger\Collectors\Request\HeadersCollector;
use Stryber\Logger\Collectors\Request\RouteCollector;
use Stryber\Logger\Collectors\Response\ResponseDataCollector;
use Stryber\Logger\Collectors\Response\StatusCodeCollector;

return [
    'middleware' => [
        'collectors' => [
            'request' => [
                HeadersCollector::class,
                RouteCollector::class,
                DataCollector::class,
            ],
            'response' => [
                StatusCodeCollector::class,
                ResponseDataCollector::class,
            ],
        ],
    ],
    'collectors' => [
        HeadersCollector::class => [
            'contextKey' => 'headers',
            'ignore' => [
                'cookie',
                'authorization',
            ],
        ],
        RouteCollector::class => [
            'contextKey' => 'route',
        ],
        DataCollector::class => [
            'contextKey' => 'data',
            'ignore' => [
                'password',
            ],
        ],
        StatusCodeCollector::class => [
            'contextKey' => 'status_code',
        ],
        ResponseDataCollector::class => [
            'contextKey' => 'data',
            'ignore' => [
                'token',
            ],
        ],
    ],
];
