<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Application\UpdateEventResultsCache;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final readonly class UpdateEventResultsCacheCommand implements Command
{
    private function __construct(
        private string $eventId,
    ) {
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public static function fromScalars(string $eventId): self
    {
        return new self($eventId);
    }
}
