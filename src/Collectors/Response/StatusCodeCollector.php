<?php

declare(strict_types=1);

namespace Stryber\Logger\Collectors\Response;

use Stryber\Logger\Collectors\Collector;
use Stryber\Logger\Collectors\Passable;

final class StatusCodeCollector extends Collector
{
    protected function getContext(Passable $passable): int
    {
        return $passable->input->getStatusCode();
    }
}
