<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Result\Domain\DomainEvent\ClassificationCreatedDomainEvent;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableBoolValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableFloatValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveFloatValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class Classification extends AggregateRoot
{
    private function __construct(
        private readonly UuidValueObject $id,
        private readonly UuidValueObject $entry,
        private readonly PositiveIntValueObject $finishPosition,
        private readonly NullableIntValueObject $gridPosition,
        private readonly PositiveIntValueObject $laps,
        private readonly PositiveFloatValueObject $points,
        private readonly PositiveFloatValueObject $time,
        private readonly NullableStringValueObject $classifiedStatus,
        private readonly PositiveFloatValueObject $averageLapSpeed,
        private readonly NullableFloatValueObject $fastestLapTime,
        private readonly PositiveFloatValueObject $gapTimeToLead,
        private readonly PositiveFloatValueObject $gapTimeToNext,
        private readonly PositiveIntValueObject $gapLapsToLead,
        private readonly PositiveIntValueObject $gapLapsToNext,
        private readonly NullableIntValueObject $bestLap,
        private readonly NullableFloatValueObject $bestTime,
        private readonly NullableBoolValueObject $bestIsFastest,
        private readonly NullableFloatValueObject $bestSpeed,
    ) {
    }

    public static function create(
        UuidValueObject $id,
        UuidValueObject $entry,
        PositiveIntValueObject $finishPosition,
        NullableIntValueObject $gridPosition,
        PositiveIntValueObject $laps,
        PositiveFloatValueObject $points,
        PositiveFloatValueObject $time,
        NullableStringValueObject $classifiedStatus,
        PositiveFloatValueObject $averageLapSpeed,
        NullableFloatValueObject $fastestLapTime,
        PositiveFloatValueObject $gapTimeToLead,
        PositiveFloatValueObject $gapTimeToNext,
        PositiveIntValueObject $gapLapsToLead,
        PositiveIntValueObject $gapLapsToNext,
        NullableIntValueObject $bestLap,
        NullableFloatValueObject $bestTime,
        NullableBoolValueObject $bestIsFastest,
        NullableFloatValueObject $bestSpeed,
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
            $bestSpeed,
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
        PositiveIntValueObject $finishPosition,
        NullableIntValueObject $gridPosition,
        PositiveIntValueObject $laps,
        PositiveFloatValueObject $points,
        PositiveFloatValueObject $time,
        NullableStringValueObject $classifiedStatus,
        PositiveFloatValueObject $averageLapSpeed,
        NullableFloatValueObject $fastestLapTime,
        PositiveFloatValueObject $gapTimeToLead,
        PositiveFloatValueObject $gapTimeToNext,
        PositiveIntValueObject $gapLapsToLead,
        PositiveIntValueObject $gapLapsToNext,
        NullableIntValueObject $bestLap,
        NullableFloatValueObject $bestTime,
        NullableBoolValueObject $bestIsFastest,
        NullableFloatValueObject $bestSpeed,
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
            $bestSpeed,
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

    public function finishPosition(): PositiveIntValueObject
    {
        return $this->finishPosition;
    }

    public function gridPosition(): NullableIntValueObject
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

    public function classifiedStatus(): NullableStringValueObject
    {
        return $this->classifiedStatus;
    }

    public function averageLapSpeed(): PositiveFloatValueObject
    {
        return $this->averageLapSpeed;
    }

    public function fastestLapTime(): NullableFloatValueObject
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

    public function bestLap(): NullableIntValueObject
    {
        return $this->bestLap;
    }

    public function bestTime(): NullableFloatValueObject
    {
        return $this->bestTime;
    }

    public function bestIsFastest(): NullableBoolValueObject
    {
        return $this->bestIsFastest;
    }

    public function bestSpeed(): NullableFloatValueObject
    {
        return $this->bestSpeed;
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
            'best_speed'        => $this->bestSpeed->value(),
        ];
    }
}
