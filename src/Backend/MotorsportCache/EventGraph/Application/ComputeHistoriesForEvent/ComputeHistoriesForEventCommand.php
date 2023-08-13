<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeHistoriesForEvent;

use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeGraphCommand;

final readonly class ComputeHistoriesForEventCommand extends ComputeGraphCommand
{
    public static function fromScalars(string $event): self
    {
        return new self($event);
    }
}
