<?php

declare(strict_types=1);

namespace Stryber\Logger\Collectors\Request;

use Stryber\Logger\Collectors\CleaningCollector;
use Stryber\Logger\Collectors\Passable;

final class DataCollector extends CleaningCollector
{
    protected function getRawContext(Passable $passable): array
    {
        return $passable->input->all();
    }
}
