<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\DomainEvent\StandingCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\Enum\StandingType;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\FloatValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class Standing extends AggregateRoot
{
    private function __construct(
        private readonly UuidValueObject $id,
        private readonly UuidValueObject $season,
        private readonly UuidValueObject $standee,
        private readonly NullableUuidValueObject $country,
        private readonly NullableStringValueObject $seriesClass,
        private readonly PositiveIntValueObject $position,
        private readonly FloatValueObject $points,
        private readonly StandingType $standingType,
    ) {
    }

    public static function create(
        UuidValueObject $id,
        UuidValueObject $season,
        UuidValueObject $standee,
        NullableUuidValueObject $country,
        NullableStringValueObject $seriesClass,
        PositiveIntValueObject $position,
        FloatValueObject $points,
        StandingType $standingType,
    ): self {
        $standeeStanding = new self($id, $season, $standee, $country, $seriesClass, $position, $points, $standingType);

        $standeeStanding->record(new StandingCreatedDomainEvent($id, $standingType));

        return $standeeStanding;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        UuidValueObject $id,
        UuidValueObject $season,
        UuidValueObject $standee,
        NullableUuidValueObject $country,
        NullableStringValueObject $seriesClass,
        PositiveIntValueObject $position,
        FloatValueObject $points,
        StandingType $standingType,
    ): self {
        return new self($id, $season, $standee, $country, $seriesClass, $position, $points, $standingType);
    }

    public function id(): UuidValueObject
    {
        return $this->id;
    }

    public function season(): UuidValueObject
    {
        return $this->season;
    }

    public function standee(): UuidValueObject
    {
        return $this->standee;
    }

    public function country(): NullableUuidValueObject
    {
        return $this->country;
    }

    public function seriesClass(): NullableStringValueObject
    {
        return $this->seriesClass;
    }

    public function position(): PositiveIntValueObject
    {
        return $this->position;
    }

    public function points(): FloatValueObject
    {
        return $this->points;
    }

    public function standingType(): StandingType
    {
        return $this->standingType;
    }

    public function mappedData(): array
    {
        return [
            'id'           => $this->id->value(),
            'season'       => $this->season->value(),
            'standee'      => $this->standee->value(),
            'country'      => $this->country->value(),
            'series_class' => $this->seriesClass->value(),
            'position'     => $this->position->value(),
            'points'       => $this->points->value(),
        ];
    }
}
