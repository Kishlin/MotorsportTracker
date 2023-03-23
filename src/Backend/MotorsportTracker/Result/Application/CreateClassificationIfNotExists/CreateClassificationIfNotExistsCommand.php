<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Application\CreateClassificationIfNotExists;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveFloatValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class CreateClassificationIfNotExistsCommand implements Command
{
    private function __construct(
        private readonly string $entry,
        private readonly int $finishPosition,
        private readonly int $gridPosition,
        private readonly int $laps,
        private readonly float $points,
        private readonly float $time,
        private readonly string $classifiedStatus,
        private readonly float $averageLapSpeed,
        private readonly float $fastestLapTime,
        private readonly float $gapTimeToLead,
        private readonly float $gapTimeToNext,
        private readonly int $gapLapsToLead,
        private readonly int $gapLapsToNext,
        private readonly int $bestLap,
        private readonly float $bestTime,
        private readonly bool $bestIsFastest,
    ) {
    }

    public function entry(): UuidValueObject
    {
        return new UuidValueObject($this->entry);
    }

    public function finishPosition(): StrictlyPositiveIntValueObject
    {
        return new StrictlyPositiveIntValueObject($this->finishPosition);
    }

    public function gridPosition(): StrictlyPositiveIntValueObject
    {
        return new StrictlyPositiveIntValueObject($this->gridPosition);
    }

    public function laps(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->laps);
    }

    public function points(): PositiveFloatValueObject
    {
        return new PositiveFloatValueObject($this->points);
    }

    public function time(): PositiveFloatValueObject
    {
        return new PositiveFloatValueObject($this->time);
    }

    public function classifiedStatus(): StringValueObject
    {
        return new StringValueObject($this->classifiedStatus);
    }

    public function averageLapSpeed(): PositiveFloatValueObject
    {
        return new PositiveFloatValueObject($this->averageLapSpeed);
    }

    public function fastestLapTime(): PositiveFloatValueObject
    {
        return new PositiveFloatValueObject($this->fastestLapTime);
    }

    public function gapTimeToLead(): PositiveFloatValueObject
    {
        return new PositiveFloatValueObject($this->gapTimeToLead);
    }

    public function gapTimeToNext(): PositiveFloatValueObject
    {
        return new PositiveFloatValueObject($this->gapTimeToNext);
    }

    public function gapLapsToLead(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->gapLapsToLead);
    }

    public function gapLapsToNext(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->gapLapsToNext);
    }

    public function bestLap(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->bestLap);
    }

    public function bestTime(): PositiveFloatValueObject
    {
        return new PositiveFloatValueObject($this->bestTime);
    }

    public function bestIsFastest(): BoolValueObject
    {
        return new BoolValueObject($this->bestIsFastest);
    }

    public static function fromScalars(
        string $entry,
        int $finishPosition,
        int $gridPosition,
        int $laps,
        float $points,
        float $time,
        string $classifiedStatus,
        float $averageLapSpeed,
        float $fastestLapTime,
        float $gapTimeToLead,
        float $gapTimeToNext,
        int $gapLapsToLead,
        int $gapLapsToNext,
        int $bestLap,
        float $bestTime,
        bool $bestIsFastest,
    ): self {
        return new self(
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
}
