<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final readonly class ComputeLapByLapGraphCommand implements Command
{
    private function __construct(
        private string $eventId,
        private ?float $maxTimeRatio,
    ) {
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public function maxTimeRatio(): ?float
    {
        return $this->maxTimeRatio;
    }

    public static function fromScalars(string $eventId, ?float $maxTimeRatio = null): self
    {
        return new self($eventId, $maxTimeRatio);
    }
}
