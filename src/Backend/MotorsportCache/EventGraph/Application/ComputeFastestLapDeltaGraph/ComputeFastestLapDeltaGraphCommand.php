<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeFastestLapDeltaGraph;

use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeGraphCommand;

final readonly class ComputeFastestLapDeltaGraphCommand extends ComputeGraphCommand
{
    public static function fromScalars(string $eventId): self
    {
        return new self($eventId);
    }
}
