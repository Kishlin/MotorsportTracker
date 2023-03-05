<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Event\Domain\DomainEvent\EventCreatedDomainEvent;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableDateTimeValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class Event extends AggregateRoot
{
    private function __construct(
        private readonly UuidValueObject $id,
        private readonly UuidValueObject $seasonId,
        private readonly UuidValueObject $venueId,
        private readonly PositiveIntValueObject $index,
        private readonly StringValueObject $slug,
        private readonly StringValueObject $name,
        private readonly NullableStringValueObject $shortName,
        private readonly NullableDateTimeValueObject $startDate,
        private readonly NullableDateTimeValueObject $endDate,
    ) {
    }

    public static function create(
        UuidValueObject $id,
        UuidValueObject $seasonId,
        UuidValueObject $venueId,
        PositiveIntValueObject $index,
        StringValueObject $slug,
        StringValueObject $name,
        NullableStringValueObject $shortName,
        NullableDateTimeValueObject $startDate,
        NullableDateTimeValueObject $endDate,
    ): self {
        $event = new self($id, $seasonId, $venueId, $index, $slug, $name, $shortName, $startDate, $endDate);

        $event->record(new EventCreatedDomainEvent($id));

        return $event;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        UuidValueObject $id,
        UuidValueObject $seasonId,
        UuidValueObject $venueId,
        PositiveIntValueObject $index,
        StringValueObject $slug,
        StringValueObject $name,
        NullableStringValueObject $shortName,
        NullableDateTimeValueObject $startDate,
        NullableDateTimeValueObject $endDate,
    ): self {
        return new self($id, $seasonId, $venueId, $index, $slug, $name, $shortName, $startDate, $endDate);
    }

    public function id(): UuidValueObject
    {
        return $this->id;
    }

    public function seasonId(): UuidValueObject
    {
        return $this->seasonId;
    }

    public function venueId(): UuidValueObject
    {
        return $this->venueId;
    }

    public function index(): PositiveIntValueObject
    {
        return $this->index;
    }

    public function slug(): StringValueObject
    {
        return $this->slug;
    }

    public function name(): StringValueObject
    {
        return $this->name;
    }

    public function shortName(): NullableStringValueObject
    {
        return $this->shortName;
    }

    public function startDate(): NullableDateTimeValueObject
    {
        return $this->startDate;
    }

    public function endDate(): NullableDateTimeValueObject
    {
        return $this->endDate;
    }
}
