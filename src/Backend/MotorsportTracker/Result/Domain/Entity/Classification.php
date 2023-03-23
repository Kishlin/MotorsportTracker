<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Result\Domain\DomainEvent\ClassificationCreatedDomainEvent;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveFloatValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class Classification extends AggregateRoot
{
    private function __construct(
        private readonly UuidValueObject $id,
        private readonly UuidValueObject $entry,
        private readonly StrictlyPositiveIntValueObject $finishPosition,
        private readonly StrictlyPositiveIntValueObject $gridPosition,
        private readonly PositiveIntValueObject $laps,
        private readonly PositiveFloatValueObject $points,
        private readonly PositiveFloatValueObject $time,
        private readonly StringValueObject $classifiedStatus,
        private readonly PositiveFloatValueObject $averageLapSpeed,
        private readonly PositiveFloatValueObject $fastestLapTime,
        private readonly PositiveFloatValueObject $gapTimeToLead,
        private readonly PositiveFloatValueObject $gapTimeToNext,
        private readonly PositiveIntValueObject $gapLapsToLead,
        private readonly PositiveIntValueObject $gapLapsToNext,
        private readonly PositiveIntValueObject $bestLap,
        private readonly PositiveFloatValueObject $bestTime,
        private readonly BoolValueObject $bestIsFastest,
    ) {
    }

    public static function create(
        UuidValueObject $id,
        UuidValueObject $entry,
        StrictlyPositiveIntValueObject $finishPosition,
        StrictlyPositiveIntValueObject $gridPosition,
        PositiveIntValueObject $laps,
        PositiveFloatValueObject $points,
        PositiveFloatValueObject $time,
        StringValueObject $classifiedStatus,
        PositiveFloatValueObject $averageLapSpeed,
        PositiveFloatValueObject $fastestLapTime,
        PositiveFloatValueObject $gapTimeToLead,
        PositiveFloatValueObject $gapTimeToNext,
        PositiveIntValueObject $gapLapsToLead,
        PositiveIntValueObject $gapLapsToNext,
        PositiveIntValueObject $bestLap,
        PositiveFloatValueObject $bestTime,
        BoolValueObject $bestIsFastest,
    ): self {
        $classification = new self(
            $id,
            $entry,
            $finishPosition,
            $gridPosition,
            $laps,
            $points,
            $time,
            $classifiedStatus,
            $averageLapSpeed,
            $fastestLapTime,
            $gapTimeToLead,
            $gapTimeToNext,
            $gapLapsToLead,
            $gapLapsToNext,
            $bestLap,
            $bestTime,
            $bestIsFastest,
        );

        $classification->record(new ClassificationCreatedDomainEvent($id));

        return $classification;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        UuidValueObject $id,
        UuidValueObject $entry,
        StrictlyPositiveIntValueObject $finishPosition,
        StrictlyPositiveIntValueObject $gridPosition,
        PositiveIntValueObject $laps,
        PositiveFloatValueObject $points,
        PositiveFloatValueObject $time,
        StringValueObject $classifiedStatus,
        PositiveFloatValueObject $averageLapSpeed,
        PositiveFloatValueObject $fastestLapTime,
        PositiveFloatValueObject $gapTimeToLead,
        PositiveFloatValueObject $gapTimeToNext,
        PositiveIntValueObject $gapLapsToLead,
        PositiveIntValueObject $gapLapsToNext,
        PositiveIntValueObject $bestLap,
        PositiveFloatValueObject $bestTime,
        BoolValueObject $bestIsFastest,
    ): self {
        return new self(
            $id,
            $entry,
            $finishPosition,
            $gridPosition,
            $laps,
            $points,
            $time,
            $classifiedStatus,
            $averageLapSpeed,
            $fastestLapTime,
            $gapTimeToLead,
            $gapTimeToNext,
            $gapLapsToLead,
            $gapLapsToNext,
            $bestLap,
            $bestTime,
            $bestIsFastest,
        );
    }

    public function id(): UuidValueObject
    {
        return $this->id;
    }

    public function entry(): UuidValueObject
    {
        return $this->entry;
    }

    public function finishPosition(): StrictlyPositiveIntValueObject
    {
        return $this->finishPosition;
    }

    public function gridPosition(): StrictlyPositiveIntValueObject
    {
        return $this->gridPosition;
    }

    public function laps(): PositiveIntValueObject
    {
        return $this->laps;
    }

    public function points(): PositiveFloatValueObject
    {
        return $this->points;
    }

    public function time(): PositiveFloatValueObject
    {
        return $this->time;
    }

    public function classifiedStatus(): StringValueObject
    {
        return $this->classifiedStatus;
    }

    public function averageLapSpeed(): PositiveFloatValueObject
    {
        return $this->averageLapSpeed;
    }

    public function fastestLapTime(): PositiveFloatValueObject
    {
        return $this->fastestLapTime;
    }

    public function gapTimeToLead(): PositiveFloatValueObject
    {
        return $this->gapTimeToLead;
    }

    public function gapTimeToNext(): PositiveFloatValueObject
    {
        return $this->gapTimeToNext;
    }

    public function gapLapsToLead(): PositiveIntValueObject
    {
        return $this->gapLapsToLead;
    }

    public function gapLapsToNext(): PositiveIntValueObject
    {
        return $this->gapLapsToNext;
    }

    public function bestLap(): PositiveIntValueObject
    {
        return $this->bestLap;
    }

    public function bestTime(): PositiveFloatValueObject
    {
        return $this->bestTime;
    }

    public function bestIsFastest(): BoolValueObject
    {
        return $this->bestIsFastest;
    }

    public function mappedData(): array
    {
        return [
            'id'                => $this->id->value(),
            'entry'             => $this->entry->value(),
            'finish_position'   => $this->finishPosition->value(),
            'grid_position'     => $this->gridPosition->value(),
            'laps'              => $this->laps->value(),
            'points'            => $this->points->value(),
            'lap_time'          => $this->time->value(),
            'classified_status' => $this->classifiedStatus->value(),
            'average_lap_speed' => $this->averageLapSpeed->value(),
            'fastest_lap_time'  => $this->fastestLapTime->value(),
            'gap_time_to_lead'  => $this->gapTimeToLead->value(),
            'gap_time_to_next'  => $this->gapTimeToNext->value(),
            'gap_laps_to_lead'  => $this->gapLapsToLead->value(),
            'gap_laps_to_next'  => $this->gapLapsToNext->value(),
            'best_lap'          => $this->bestLap->value(),
            'best_time'         => $this->bestTime->value(),
            'best_is_fastest'   => $this->bestIsFastest->value() ? 1 : 0,
        ];
    }
}
