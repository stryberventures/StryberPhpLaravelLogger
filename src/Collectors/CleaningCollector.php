<?php

declare(strict_types=1);

namespace Stryber\Logger\Collectors;

abstract class CleaningCollector extends Collector
{
    private array $ignore;

    public function __construct(string $contextKey, array $ignore)
    {
        $this->ignore = $ignore;

        parent::__construct($contextKey);
    }

    abstract protected function getRawContext(Passable $passable): array;

    final protected function getContext(Passable $passable)
    {
        return $this->cleanContext($this->getRawContext($passable));
    }

    private function cleanContext(array $context): array
    {
        return array_diff_key($context, array_flip($this->ignore));
    }
}
