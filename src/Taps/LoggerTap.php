<?php

declare(strict_types=1);

namespace Stryber\Logger\Taps;

use Illuminate\Log\Logger;

interface LoggerTap
{
    public function __invoke(Logger $logger): void;
}
