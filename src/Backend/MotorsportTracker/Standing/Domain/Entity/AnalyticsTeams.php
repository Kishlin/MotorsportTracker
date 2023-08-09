<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\DomainEvent\AnalyticsTeamsCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\DTO\AnalyticsTeamsStatsDTO;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\FloatValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class AnalyticsTeams extends AggregateRoot
{
    private function __construct(
        private readonly UuidValueObject $id,
        private readonly UuidValueObject $season,
        private readonly UuidValueObject $driver,
        private readonly NullableUuidValueObject $country,
        private readonly PositiveIntValueObject $position,
        private readonly FloatValueObject $points,
        private readonly PositiveIntValueObject $classWins,
        private readonly PositiveIntValueObject $fastestLaps,
        private readonly PositiveIntValueObject $finalAppearances,
        private readonly PositiveIntValueObject $finishesOneAndTwo,
        private readonly PositiveIntValueObject $podiums,
        private readonly PositiveIntValueObject $poles,
        private readonly PositiveIntValueObject $qualifiesOneAndTwo,
        private readonly PositiveIntValueObject $racesLed,
        private readonly PositiveIntValueObject $ralliesLed,
        private readonly PositiveIntValueObject $retirements,
        private readonly PositiveIntValueObject $semiFinalAppearances,
        private readonly PositiveIntValueObject $stageWins,
        private readonly PositiveIntValueObject $starts,
        private readonly PositiveIntValueObject $top10s,
        private readonly PositiveIntValueObject $top5s,
        private readonly PositiveIntValueObject $wins,
        private readonly FloatValueObject $winsPercentage,
    ) {
    }

    public static function create(
        UuidValueObject $id,
        UuidValueObject $season,
        UuidValueObject $driver,
        NullableUuidValueObject $country,
        PositiveIntValueObject $position,
        FloatValueObject $points,
        AnalyticsTeamsStatsDTO $analytics,
    ): self {
        $analytics = new self(
            $id,
            $season,
            $driver,
            $country,
            $position,
            $points,
            $analytics->classWins(),
            $analytics->fastestLaps(),
            $analytics->finalAppearances(),
            $analytics->finishesOneAndTwo(),
            $analytics->podiums(),
            $analytics->poles(),
            $analytics->qualifiesOneAndTwo(),
            $analytics->racesLed(),
            $analytics->ralliesLed(),
            $analytics->retirements(),
            $analytics->semiFinalAppearances(),
            $analytics->stageWins(),
            $analytics->starts(),
            $analytics->top10s(),
            $analytics->top5s(),
            $analytics->wins(),
            $analytics->winsPercentage(),
        );

        $analytics->record(new AnalyticsTeamsCreatedDomainEvent($id));

        return $analytics;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        UuidValueObject $id,
        UuidValueObject $season,
        UuidValueObject $driver,
        NullableUuidValueObject $country,
        PositiveIntValueObject $position,
        FloatValueObject $points,
        PositiveIntValueObject $classWins,
        PositiveIntValueObject $fastestLaps,
        PositiveIntValueObject $finalAppearances,
        PositiveIntValueObject $finishesOneAndTwo,
        PositiveIntValueObject $podiums,
        PositiveIntValueObject $poles,
        PositiveIntValueObject $qualifiesOneAndTwo,
        PositiveIntValueObject $racesLed,
        PositiveIntValueObject $ralliesLed,
        PositiveIntValueObject $retirements,
        PositiveIntValueObject $semiFinalAppearances,
        PositiveIntValueObject $stageWins,
        PositiveIntValueObject $starts,
        PositiveIntValueObject $top10s,
        PositiveIntValueObject $top5s,
        PositiveIntValueObject $wins,
        FloatValueObject $winsPercentage,
    ): self {
        $analytics = new self(
            $id,
            $season,
            $driver,
            $country,
            $position,
            $points,
            $classWins,
            $fastestLaps,
            $finalAppearances,
            $finishesOneAndTwo,
            $podiums,
            $poles,
            $qualifiesOneAndTwo,
            $racesLed,
            $ralliesLed,
            $retirements,
            $semiFinalAppearances,
            $stageWins,
            $starts,
            $top10s,
            $top5s,
            $wins,
            $winsPercentage,
        );

        $analytics->record(new AnalyticsTeamsCreatedDomainEvent($id));

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

    public function driver(): UuidValueObject
    {
        return $this->driver;
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

    public function classWins(): PositiveIntValueObject
    {
        return $this->classWins;
    }

    public function fastestLaps(): PositiveIntValueObject
    {
        return $this->fastestLaps;
    }

    public function finalAppearances(): PositiveIntValueObject
    {
        return $this->finalAppearances;
    }

    public function finishesOneAndTwo(): PositiveIntValueObject
    {
        return $this->finishesOneAndTwo;
    }

    public function podiums(): PositiveIntValueObject
    {
        return $this->podiums;
    }

    public function poles(): PositiveIntValueObject
    {
        return $this->poles;
    }

    public function qualifiesOneAndTwo(): PositiveIntValueObject
    {
        return $this->qualifiesOneAndTwo;
    }

    public function racesLed(): PositiveIntValueObject
    {
        return $this->racesLed;
    }

    public function ralliesLed(): PositiveIntValueObject
    {
        return $this->ralliesLed;
    }

    public function retirements(): PositiveIntValueObject
    {
        return $this->retirements;
    }

    public function semiFinalAppearances(): PositiveIntValueObject
    {
        return $this->semiFinalAppearances;
    }

    public function stageWins(): PositiveIntValueObject
    {
        return $this->stageWins;
    }

    public function starts(): PositiveIntValueObject
    {
        return $this->starts;
    }

    public function top10s(): PositiveIntValueObject
    {
        return $this->top10s;
    }

    public function top5s(): PositiveIntValueObject
    {
        return $this->top5s;
    }

    public function wins(): PositiveIntValueObject
    {
        return $this->wins;
    }

    public function winsPercentage(): FloatValueObject
    {
        return $this->winsPercentage;
    }

    public function mappedData(): array
    {
        return [
            'id'                     => $this->id->value(),
            'season'                 => $this->season->value(),
            'team'                   => $this->driver->value(),
            'country'                => $this->country->value(),
            'position'               => $this->position->value(),
            'points'                 => $this->points->value(),
            'class_wins'             => $this->classWins->value(),
            'fastest_laps'           => $this->fastestLaps->value(),
            'final_appearances'      => $this->finalAppearances->value(),
            'finishes_one_and_two'   => $this->finishesOneAndTwo()->value(),
            'podiums'                => $this->podiums->value(),
            'poles'                  => $this->poles->value(),
            'qualifies_one_and_two'  => $this->qualifiesOneAndTwo()->value(),
            'races_led'              => $this->racesLed->value(),
            'rallies_led'            => $this->ralliesLed->value(),
            'retirements'            => $this->retirements->value(),
            'semi_final_appearances' => $this->semiFinalAppearances->value(),
            'stage_wins'             => $this->stageWins->value(),
            'starts'                 => $this->starts->value(),
            'top10s'                 => $this->top10s->value(),
            'top5s'                  => $this->top5s->value(),
            'wins'                   => $this->wins->value(),
            'wins_percentage'        => $this->winsPercentage->value(),
        ];
    }
}
