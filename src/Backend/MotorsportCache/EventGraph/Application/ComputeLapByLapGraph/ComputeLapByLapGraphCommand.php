<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final class ComputeLapByLapGraphCommand implements Command
{
    private function __construct(
        private readonly string $eventId,
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
