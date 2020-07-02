<?php

declare(strict_types=1);

namespace Stryber\Logger\Wrappers;

use Gelf\Message;
use Monolog\Formatter\GelfMessageFormatter;

final class GelfFormatterWrapper extends GelfMessageFormatter
{
    public function format(array $record): Message
    {
        $message = parent::format($record);

        return new GelfMessageWrapper($message);
    }
}
