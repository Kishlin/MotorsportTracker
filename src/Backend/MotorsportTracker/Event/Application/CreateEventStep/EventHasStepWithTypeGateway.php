<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventStep;

use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepEventId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepTypeId;

interface EventHasStepWithTypeGateway
{
    public function eventHasStepWithType(EventStepEventId $eventId, EventStepTypeId $typeId): bool;
}
