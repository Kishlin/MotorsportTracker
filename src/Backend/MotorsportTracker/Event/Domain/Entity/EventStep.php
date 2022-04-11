<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Event\Domain\DomainEvent\EventStepCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepDateTime;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepEventId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepTypeId;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;

final class EventStep extends AggregateRoot
{
    private function __construct(
        private EventStepId $id,
        private EventStepTypeId $typeId,
        private EventStepEventId $eventId,
        private EventStepDateTime $dateTime,
    ) {
    }

    public static function create(
        EventStepId $id,
        EventStepTypeId $typeId,
        EventStepEventId $eventId,
        EventStepDateTime $dateTime,
    ): self {
        $eventStep = new self($id, $typeId, $eventId, $dateTime);

        $eventStep->record(new EventStepCreatedDomainEvent($id));

        return $eventStep;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        EventStepId $id,
        EventStepTypeId $typeId,
        EventStepEventId $eventId,
        EventStepDateTime $dateTime,
    ): self {
        return new self($id, $typeId, $eventId, $dateTime);
    }

    public function id(): EventStepId
    {
        return $this->id;
    }

    public function typeId(): EventStepTypeId
    {
        return $this->typeId;
    }

    public function eventId(): EventStepEventId
    {
        return $this->eventId;
    }

    public function dateTime(): EventStepDateTime
    {
        return $this->dateTime;
    }
}
