<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Event\Domain\Entity;

use JsonException;
use Kishlin\Backend\MotorsportCache\Event\Domain\DomainEvent\SeasonEventsCreatedDomainEvent;
use Kishlin\Backend\MotorsportCache\Event\Domain\ValueObject\SeasonEventList;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class SeasonEvents extends AggregateRoot
{
    private function __construct(
        private readonly UuidValueObject $id,
        private readonly StringValueObject $championship,
        private readonly StrictlyPositiveIntValueObject $year,
        private readonly SeasonEventList $events,
    ) {
    }

    public static function create(
        UuidValueObject $id,
        StringValueObject $championship,
        StrictlyPositiveIntValueObject $year,
        SeasonEventList $events,
    ): self {
        $seasonEvents = new self($id, $championship, $year, $events);

        $seasonEvents->record(new SeasonEventsCreatedDomainEvent($id));

        return $seasonEvents;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        UuidValueObject $id,
        StringValueObject $championship,
        StrictlyPositiveIntValueObject $year,
        SeasonEventList $events,
    ): self {
        return new self($id, $championship, $year, $events);
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

    public function events(): SeasonEventList
    {
        return $this->events;
    }

    /**
     * @throws JsonException
     */
    public function mappedData(): array
    {
        return [
            'id'           => $this->id->value(),
            'championship' => $this->championship->value(),
            'year'         => $this->year->value(),
            'events'       => $this->events->asString(),
        ];
    }
}
