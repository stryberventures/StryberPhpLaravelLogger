<?php

declare(strict_types=1);

namespace Stryber\Logger\Taps;

use Illuminate\Contracts\Container\Container;
use Illuminate\Log\Logger;
use Stryber\Logger\Wrappers\GelfFormatterWrapper;

final class FormatterConfigurator implements LoggerTap
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function __invoke(Logger $logger): void
    {
        /** @var \Monolog\Logger $logger */
        foreach ($logger->getHandlers() as $handler) {
            $formatter = $this->container->make(GelfFormatterWrapper::class);
            $handler->setFormatter($formatter);
        }
    }
}
