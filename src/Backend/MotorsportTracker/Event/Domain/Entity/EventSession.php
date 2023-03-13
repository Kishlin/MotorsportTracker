<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Event\Domain\DomainEvent\EventSessionCreatedDomainEvent;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableDateTimeValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class EventSession extends AggregateRoot
{
    private function __construct(
        private readonly UuidValueObject $id,
        private readonly UuidValueObject $eventId,
        private readonly UuidValueObject $typeId,
        private readonly BoolValueObject $hasResult,
        private readonly NullableDateTimeValueObject $startDate,
        private readonly NullableDateTimeValueObject $endDate,
        private readonly NullableUuidValueObject $ref,
    ) {
    }

    public static function create(
        UuidValueObject $id,
        UuidValueObject $eventId,
        UuidValueObject $typeId,
        BoolValueObject $hasResult,
        NullableDateTimeValueObject $startDate,
        NullableDateTimeValueObject $endDate,
        NullableUuidValueObject $ref,
    ): self {
        $eventSession = new self($id, $eventId, $typeId, $hasResult, $startDate, $endDate, $ref);

        $eventSession->record(new EventSessionCreatedDomainEvent($id));

        return $eventSession;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        UuidValueObject $id,
        UuidValueObject $eventId,
        UuidValueObject $typeId,
        BoolValueObject $hasResult,
        NullableDateTimeValueObject $startDate,
        NullableDateTimeValueObject $endDate,
        NullableUuidValueObject $ref,
    ): self {
        return new self($id, $eventId, $typeId, $hasResult, $startDate, $endDate, $ref);
    }

    public function id(): UuidValueObject
    {
        return $this->id;
    }

    public function typeId(): UuidValueObject
    {
        return $this->typeId;
    }

    public function eventId(): UuidValueObject
    {
        return $this->eventId;
    }

    public function hasResult(): BoolValueObject
    {
        return $this->hasResult;
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
            'type'       => $this->typeId->value(),
            'event'      => $this->eventId->value(),
            'has_result' => $this->hasResult->value() ? 1 : 0,
            'start_date' => $this->startDate->value()?->format('Y-m-d H:i:s'),
            'end_date'   => $this->endDate->value()?->format('Y-m-d H:i:s'),
            'ref'        => $this->ref->value(),
        ];
    }
}
