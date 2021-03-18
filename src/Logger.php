<?php

declare(strict_types=1);

namespace Stryber\Logger;

use Psr\Log\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

final class Logger implements LoggerInterface
{
    private LoggerInterface $messageLogger;
    private LoggerInterface $errorLogger;

    public function __construct(LoggerInterface $messageLogger, LoggerInterface $errorLogger)
    {
        $this->messageLogger = $messageLogger;
        $this->errorLogger = $errorLogger;
    }

    public function emergency(mixed $message, array $context = array()): void
    {
        $this->errorLogger->emergency($message, $context);
    }

    public function alert(mixed $message, array $context = array()): void
    {
        $this->errorLogger->alert($message, $context);
    }

    public function critical(mixed $message, array $context = array()): void
    {
        $this->errorLogger->critical($message, $context);
    }

    public function error(mixed $message, array $context = array()): void
    {
        $this->errorLogger->error($message, $context);
    }

    public function warning(mixed $message, array $context = array()): void
    {
        $this->errorLogger->warning($message, $context);
    }

    public function notice(mixed $message, array $context = array()): void
    {
        $this->messageLogger->notice($message, $context);
    }

    public function info(mixed $message, array $context = array()): void
    {
        $this->messageLogger->info($message, $context);
    }

    public function debug(mixed $message, array $context = array()): void
    {
        $this->messageLogger->debug($message, $context);
    }

    public function log($level, mixed $message, array $context = []): void
    {
        match ($level) {
            LogLevel::DEBUG => $this->debug($message, $context),
            LogLevel::INFO => $this->info($message, $context),
            LogLevel::NOTICE => $this->notice($message, $context),
            LogLevel::WARNING => $this->warning($message, $context),
            LogLevel::ERROR => $this->error($message, $context),
            LogLevel::CRITICAL => $this->critical($message, $context),
            LogLevel::ALERT => $this->alert($message, $context),
            LogLevel::EMERGENCY => $this->emergency($message, $context),
            default => throw new InvalidArgumentException("Unsupported log level: {$level}"),
        };
    }
}
