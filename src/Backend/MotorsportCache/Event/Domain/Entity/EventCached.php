<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Event\Domain\Entity;

use Kishlin\Backend\MotorsportCache\Event\Domain\DomainEvent\EventCachedCreatedDomainEvent;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class EventCached extends AggregateRoot
{
    private function __construct(
        private readonly UuidValueObject $id,
        private readonly StringValueObject $championship,
        private readonly StrictlyPositiveIntValueObject $year,
        private readonly StringValueObject $eventSlug,
    ) {
    }

    public static function create(
        UuidValueObject $id,
        StringValueObject $championship,
        StrictlyPositiveIntValueObject $year,
        StringValueObject $eventSlug,
    ): self {
        $event = new self($id, $championship, $year, $eventSlug);

        $event->record(new EventCachedCreatedDomainEvent($id));

        return $event;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        UuidValueObject $id,
        StringValueObject $championship,
        StrictlyPositiveIntValueObject $year,
        StringValueObject $eventSlug,
    ): self {
        return new self($id, $championship, $year, $eventSlug);
    }

    public function id(): UuidValueObject
    {
        return $this->id;
    }

    public function championship(): StringValueObject
    {
        return $this->championship;
    }

    public function year(): StrictlyPositiveIntValueObject
    {
        return $this->year;
    }

    public function eventSlug(): StringValueObject
    {
        return $this->eventSlug;
    }

    public function mappedData(): array
    {
        return [
            'id'           => $this->id->value(),
            'championship' => $this->championship->value(),
            'year'         => $this->year->value(),
            'event'        => $this->eventSlug->value(),
        ];
    }
}
