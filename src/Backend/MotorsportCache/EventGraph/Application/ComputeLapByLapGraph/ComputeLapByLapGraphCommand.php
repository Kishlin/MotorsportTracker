<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final class ComputeLapByLapGraphCommand implements Command
{
    private function __construct(
        private readonly string $eventId,
        private readonly int $maximumLapTime,
        private readonly int $minimumLapTime,
    ) {
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public function maximumLapTime(): int
    {
        return $this->maximumLapTime;
    }

    public function minimumLapTime(): int
    {
        return $this->minimumLapTime;
    }

    public static function fromScalars(string $eventId, int $maximumLapTime, int $minimumLapTime): self
    {
        return new self($eventId, $maximumLapTime, $minimumLapTime);
    }
}
