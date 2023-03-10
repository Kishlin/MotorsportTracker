<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\EntryList\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\EntryList\Domain\DomainEvent\EntryCreatedDomainEvent;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class Entry extends AggregateRoot
{
    private function __construct(
        private readonly UuidValueObject $id,
        private readonly UuidValueObject $event,
        private readonly UuidValueObject $driver,
        private readonly NullableUuidValueObject $team,
        private readonly StringValueObject $chassis,
        private readonly StringValueObject $engine,
        private readonly NullableStringValueObject $seriesName,
        private readonly NullableStringValueObject $seriesSlug,
        private readonly StringValueObject $carNumber,
    ) {
    }

    public static function create(
        UuidValueObject $id,
        UuidValueObject $event,
        UuidValueObject $driver,
        NullableUuidValueObject $team,
        StringValueObject $chassis,
        StringValueObject $engine,
        NullableStringValueObject $seriesName,
        NullableStringValueObject $seriesSlug,
        StringValueObject $carNumber,
    ): self {
        $entry = new self($id, $event, $driver, $team, $chassis, $engine, $seriesName, $seriesSlug, $carNumber);

        $entry->record(new EntryCreatedDomainEvent($id));

        return $entry;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        UuidValueObject $id,
        UuidValueObject $event,
        UuidValueObject $driver,
        NullableUuidValueObject $team,
        StringValueObject $chassis,
        StringValueObject $engine,
        NullableStringValueObject $seriesName,
        NullableStringValueObject $seriesSlug,
        StringValueObject $carNumber,
    ): self {
        return new self($id, $event, $driver, $team, $chassis, $engine, $seriesName, $seriesSlug, $carNumber);
    }

    public function id(): UuidValueObject
    {
        return $this->id;
    }

    public function event(): UuidValueObject
    {
        return $this->event;
    }

    public function driver(): UuidValueObject
    {
        return $this->driver;
    }

    public function team(): NullableUuidValueObject
    {
        return $this->team;
    }

    public function chassis(): StringValueObject
    {
        return $this->chassis;
    }

    public function engine(): StringValueObject
    {
        return $this->engine;
    }

    public function seriesName(): NullableStringValueObject
    {
        return $this->seriesName;
    }

    public function seriesSlug(): NullableStringValueObject
    {
        return $this->seriesSlug;
    }

    public function carNumber(): StringValueObject
    {
        return $this->carNumber;
    }
}
