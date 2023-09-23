<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\DomainEvent\SeasonCreatedDomainEvent;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class Season extends AggregateRoot
{
    private function __construct(
        private readonly UuidValueObject $id,
        private readonly StrictlyPositiveIntValueObject $year,
        private readonly UuidValueObject $championshipId,
        private readonly NullableUuidValueObject $ref,
    ) {
    }

    public static function create(
        UuidValueObject $id,
        StrictlyPositiveIntValueObject $year,
        UuidValueObject $championshipId,
        NullableUuidValueObject $ref,
    ): self {
        $season = new self($id, $year, $championshipId, $ref);

        $season->record(new SeasonCreatedDomainEvent($id));

        return $season;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        UuidValueObject $id,
        StrictlyPositiveIntValueObject $year,
        UuidValueObject $championshipId,
        NullableUuidValueObject $ref,
    ): self {
        return new self($id, $year, $championshipId, $ref);
    }

    public function id(): UuidValueObject
    {
        return $this->id;
    }

    public function year(): StrictlyPositiveIntValueObject
    {
        return $this->year;
    }

    public function championshipId(): UuidValueObject
    {
        return $this->championshipId;
    }

    public function ref(): NullableUuidValueObject
    {
        return $this->ref;
    }

    public function mappedData(): array
    {
        return [
            'id'     => $this->id->value(),
            'year'   => $this->year->value(),
            'series' => $this->championshipId->value(),
            'ref'    => $this->ref->value(),
        ];
    }
}
