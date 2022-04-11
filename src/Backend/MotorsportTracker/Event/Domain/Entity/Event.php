<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Event\Domain\DomainEvent\EventCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventIndex;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventLabel;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventSeasonId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventVenueId;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;

final class Event extends AggregateRoot
{
    private function __construct(
        private EventId $id,
        private EventSeasonId $seasonId,
        private EventVenueId $venueId,
        private EventIndex $index,
        private EventLabel $label,
    ) {
    }

    public static function create(
        EventId $id,
        EventSeasonId $seasonId,
        EventVenueId $eventVenueId,
        EventIndex $index,
        EventLabel $label,
    ): self {
        $event = new self($id, $seasonId, $eventVenueId, $index, $label);

        $event->record(new EventCreatedDomainEvent($id));

        return $event;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        EventId $id,
        EventSeasonId $seasonId,
        EventVenueId $eventVenueId,
        EventIndex $index,
        EventLabel $label,
    ): self {
        return new self($id, $seasonId, $eventVenueId, $index, $label);
    }

    public function id(): EventId
    {
        return $this->id;
    }

    public function seasonId(): EventSeasonId
    {
        return $this->seasonId;
    }

    public function venueId(): EventVenueId
    {
        return $this->venueId;
    }

    public function index(): EventIndex
    {
        return $this->index;
    }

    public function label(): EventLabel
    {
        return $this->label;
    }
}
