<?php

declare(strict_types=1);

namespace Stryber\Logger\Collectors\Request;

use Stryber\Logger\Collectors\Collector;
use Stryber\Logger\Collectors\Passable;

final class RouteCollector extends Collector
{
    protected function getContext(Passable $passable)
    {
        return $passable->input->route() ? $passable->input->route()->uri : $passable->input->path();
    }
}
