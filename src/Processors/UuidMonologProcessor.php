<?php

declare(strict_types=1);

namespace Stryber\Logger\Processors;

use Monolog\Processor\ProcessorInterface;
use Monolog\ResettableInterface;
use Ramsey\Uuid\UuidInterface;
use Stryber\Uuid\UuidGenerator;

final class UuidMonologProcessor implements ProcessorInterface, ResettableInterface
{
    private UuidInterface $uuid;
    private UuidGenerator $uuidGenerator;

    public function __construct(UuidGenerator $uuidGenerator)
    {
        $this->uuidGenerator = $uuidGenerator;
        $this->generateUuid();
    }

    public function __invoke(array $record): array
    {
        $record['extra']['uuid'] = (string)$this->uuid;

        return $record;
    }

    private function generateUuid(): void
    {
        $this->uuid = $this->uuidGenerator->generate();
    }

    public function reset(): void
    {
        $this->generateUuid();
    }
}
