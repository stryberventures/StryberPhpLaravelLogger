<?php

declare(strict_types=1);

namespace Stryber\Logger\Collectors;

use Closure;

abstract class Collector
{
    private string $contextKey;

    public function __construct(string $contextKey)
    {
        $this->contextKey = $contextKey;
    }

    final public function handle(Passable $passable, Closure $next)
    {
        $this->writeContext($passable, $this->getContext($passable));

        return $next($passable);
    }

    private function writeContext(Passable $passable, $context): void
    {
        $passable->output[$this->contextKey] = $context;
    }

    abstract protected function getContext(Passable $passable): mixed;

    final public function getContextKey(): string
    {
        return $this->contextKey;
    }
}
