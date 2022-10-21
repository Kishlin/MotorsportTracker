<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventStep;

use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepDateTime;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepEventId;

interface EventHasStepAtTheSameTimeGateway
{
    public function eventHasStepAtTheSameTime(EventStepEventId $eventId, EventStepDateTime $dateTime): bool;
}
