<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\DomainEvent\AnalyticsDriversCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\DTO\AnalyticsConstructorsStatsDTO;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\FloatValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class AnalyticsConstructors extends AggregateRoot
{
    private function __construct(
        private readonly UuidValueObject $id,
        private readonly UuidValueObject $season,
        private readonly UuidValueObject $constructor,
        private readonly NullableUuidValueObject $country,
        private readonly PositiveIntValueObject $position,
        private readonly FloatValueObject $points,
        private readonly PositiveIntValueObject $wins,
    ) {
    }

    public static function create(
        UuidValueObject $id,
        UuidValueObject $season,
        UuidValueObject $constructor,
        NullableUuidValueObject $country,
        PositiveIntValueObject $position,
        FloatValueObject $points,
        AnalyticsConstructorsStatsDTO $analytics,
    ): self {
        $analytics = new self(
            $id,
            $season,
            $constructor,
            $country,
            $position,
            $points,
            $analytics->wins(),
        );

        $analytics->record(new AnalyticsDriversCreatedDomainEvent($id));

        return $analytics;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        UuidValueObject $id,
        UuidValueObject $season,
        UuidValueObject $constructor,
        NullableUuidValueObject $country,
        PositiveIntValueObject $position,
        FloatValueObject $points,
        PositiveIntValueObject $wins,
    ): self {
        $analytics = new self(
            $id,
            $season,
            $constructor,
            $country,
            $position,
            $points,
            $wins,
        );

        $analytics->record(new AnalyticsDriversCreatedDomainEvent($id));

        return $analytics;
    }

    public function id(): UuidValueObject
    {
        return $this->id;
    }

    public function season(): UuidValueObject
    {
        return $this->season;
    }

    public function constructor(): UuidValueObject
    {
        return $this->constructor;
    }

    public function country(): NullableUuidValueObject
    {
        return $this->country;
    }

    public function position(): PositiveIntValueObject
    {
        return $this->position;
    }

    public function points(): FloatValueObject
    {
        return $this->points;
    }

    public function wins(): PositiveIntValueObject
    {
        return $this->wins;
    }

    public function mappedData(): array
    {
        return [
            'id'          => $this->id->value(),
            'season'      => $this->season->value(),
            'constructor' => $this->constructor->value(),
            'country'     => $this->country->value(),
            'position'    => $this->position->value(),
            'points'      => $this->points->value(),
            'wins'        => $this->wins->value(),
        ];
    }
}
