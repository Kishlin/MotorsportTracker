<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Event\Domain\DomainEvent\EventSessionCreatedDomainEvent;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableDateTimeValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class EventSession extends AggregateRoot
{
    private function __construct(
        private readonly UuidValueObject $id,
        private readonly UuidValueObject $eventId,
        private readonly UuidValueObject $typeId,
        private readonly StringValueObject $slug,
        private readonly BoolValueObject $hasResult,
        private readonly NullableDateTimeValueObject $startDate,
        private readonly NullableDateTimeValueObject $endDate,
    ) {
    }

    public static function create(
        UuidValueObject $id,
        UuidValueObject $eventId,
        UuidValueObject $typeId,
        StringValueObject $slug,
        BoolValueObject $hasResult,
        NullableDateTimeValueObject $startDate,
        NullableDateTimeValueObject $endDate,
    ): self {
        $eventSession = new self($id, $eventId, $typeId, $slug, $hasResult, $startDate, $endDate);

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
        StringValueObject $slug,
        BoolValueObject $hasResult,
        NullableDateTimeValueObject $startDate,
        NullableDateTimeValueObject $endDate,
    ): self {
        return new self($id, $eventId, $typeId, $slug, $hasResult, $startDate, $endDate);
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

    public function slug(): StringValueObject
    {
        return $this->slug;
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
}
