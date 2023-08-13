<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeTyreHistoryGraph;

use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeGraphCommand;

final readonly class ComputeTyreHistoryGraphCommand extends ComputeGraphCommand
{
    public static function fromScalars(string $eventId): self
    {
        return new self($eventId);
    }
}
