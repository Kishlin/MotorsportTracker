<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputePositionChangeGraph;

use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeGraphCommand;

final readonly class ComputePositionChangeGraphCommand extends ComputeGraphCommand
{
    public static function fromScalars(string $eventId): self
    {
        return new self($eventId);
    }
}
