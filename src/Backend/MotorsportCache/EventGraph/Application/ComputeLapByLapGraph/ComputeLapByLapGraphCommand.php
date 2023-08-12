<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph;

use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeGraphCommand;

final readonly class ComputeLapByLapGraphCommand extends ComputeGraphCommand
{
    private function __construct(
        string $eventId,
        private ?float $maxTimeRatio,
    ) {
        parent::__construct($eventId);
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
