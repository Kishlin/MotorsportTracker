<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Event\Domain\DomainEvent\EventCreatedDomainEvent;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableDateTimeValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
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
        private readonly StringValueObject $name,
        private readonly NullableStringValueObject $shortName,
        private readonly NullableStringValueObject $shortCode,
        private readonly NullableStringValueObject $status,
        private readonly NullableDateTimeValueObject $startDate,
        private readonly NullableDateTimeValueObject $endDate,
        private readonly NullableUuidValueObject $ref,
    ) {
    }

    public static function create(
        UuidValueObject $id,
        UuidValueObject $seasonId,
        UuidValueObject $venueId,
        PositiveIntValueObject $index,
        StringValueObject $name,
        NullableStringValueObject $shortName,
        NullableStringValueObject $shortCode,
        NullableStringValueObject $status,
        NullableDateTimeValueObject $startDate,
        NullableDateTimeValueObject $endDate,
        NullableUuidValueObject $ref,
    ): self {
        $event = new self($id, $seasonId, $venueId, $index, $name, $shortName, $shortCode, $status, $startDate, $endDate, $ref);

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
        StringValueObject $name,
        NullableStringValueObject $shortName,
        NullableStringValueObject $shortCode,
        NullableStringValueObject $status,
        NullableDateTimeValueObject $startDate,
        NullableDateTimeValueObject $endDate,
        NullableUuidValueObject $ref,
    ): self {
        return new self($id, $seasonId, $venueId, $index, $name, $shortName, $shortCode, $status, $startDate, $endDate, $ref);
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

    public function name(): StringValueObject
    {
        return $this->name;
    }

    public function shortName(): NullableStringValueObject
    {
        return $this->shortName;
    }

    public function shortCode(): NullableStringValueObject
    {
        return $this->shortCode;
    }

    public function status(): NullableStringValueObject
    {
        return $this->status;
    }

    public function startDate(): NullableDateTimeValueObject
    {
        return $this->startDate;
    }

    public function endDate(): NullableDateTimeValueObject
    {
        return $this->endDate;
    }

    public function ref(): NullableUuidValueObject
    {
        return $this->ref;
    }

    public function mappedData(): array
    {
        return [
            'id'         => $this->id->value(),
            'season'     => $this->seasonId->value(),
            'venue'      => $this->venueId->value(),
            'index'      => $this->index->value(),
            'name'       => $this->name->value(),
            'short_name' => $this->shortName->value(),
            'short_code' => $this->shortCode->value(),
            'status'     => $this->status->value(),
            'start_date' => $this->startDate->value()?->format('Y-m-d H:i:s'),
            'end_date'   => $this->endDate->value()?->format('Y-m-d H:i:s'),
            'ref'        => $this->ref->value(),
        ];
    }
}
