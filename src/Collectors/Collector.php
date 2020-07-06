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

    final private function writeContext(Passable $passable, $context): void
    {
        $passable->output[$this->contextKey] = $context;
    }

    /**
     * @param Passable $passable
     * @return mixed
     */
    abstract protected function getContext(Passable $passable); //: mixed

    public function getContextKey(): string
    {
        return $this->contextKey;
    }
}
