<?php

declare(strict_types=1);

namespace Stryber\Logger\Processors;

use Illuminate\Contracts\Container\Container;
use Illuminate\Log\Logger;
use Stryber\Logger\Taps\LoggerTap;

final class ProcessorsPusher implements LoggerTap
{
    private Container $container;
    private array $processors = [
        UuidMonologProcessor::class,
    ];

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function __invoke(Logger $logger): void
    {
        /** @var \Monolog\Logger $logger */
        foreach ($this->processors as $processorClass) {
            $processor = $this->container->make($processorClass);
            $logger->pushProcessor($processor);
        }
    }
}
