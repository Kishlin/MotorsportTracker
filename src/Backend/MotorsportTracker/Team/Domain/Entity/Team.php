<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Team\Domain\DomainEvent\TeamCreatedDomainEvent;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class Team extends AggregateRoot
{
    public function __construct(
        private readonly UuidValueObject $id,
        private readonly UuidValueObject $season,
        private readonly UuidValueObject $country,
        private readonly StringValueObject $name,
        private readonly NullableStringValueObject $color,
        private readonly NullableUuidValueObject $ref,
    ) {
    }

    public static function create(
        UuidValueObject $id,
        UuidValueObject $season,
        UuidValueObject $country,
        StringValueObject $name,
        NullableStringValueObject $color,
        NullableUuidValueObject $ref,
    ): self {
        $team = new self($id, $season, $country, $name, $color, $ref);

        $team->record(new TeamCreatedDomainEvent($id));

        return $team;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        UuidValueObject $id,
        UuidValueObject $season,
        UuidValueObject $country,
        StringValueObject $name,
        NullableStringValueObject $color,
        NullableUuidValueObject $ref,
    ): self {
        return new self($id, $season, $country, $name, $color, $ref);
    }

    public function id(): UuidValueObject
    {
        return $this->id;
    }

    public function season(): UuidValueObject
    {
        return $this->season;
    }

    public function country(): UuidValueObject
    {
        return $this->country;
    }

    public function name(): StringValueObject
    {
        return $this->name;
    }

    public function color(): NullableStringValueObject
    {
        return $this->color;
    }

    public function ref(): NullableUuidValueObject
    {
        return $this->ref;
    }

    public function mappedData(): array
    {
        return [
            'id'      => $this->id->value(),
            'season'  => $this->season->value(),
            'country' => $this->country->value(),
            'name'    => $this->name->value(),
            'color'   => $this->color->value(),
            'ref'     => $this->ref->value(),
        ];
    }
}
