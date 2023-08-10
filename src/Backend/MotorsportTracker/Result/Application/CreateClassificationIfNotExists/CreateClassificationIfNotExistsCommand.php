<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Application\CreateClassificationIfNotExists;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\FloatValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\IntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableBoolValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableFloatValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveFloatValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final readonly class CreateClassificationIfNotExistsCommand implements Command
{
    private function __construct(
        private string $entry,
        private int $finishPosition,
        private ?int $gridPosition,
        private int $laps,
        private float $points,
        private float $time,
        private ?string $classifiedStatus,
        private float $averageLapSpeed,
        private ?float $fastestLapTime,
        private float $gapTimeToLead,
        private float $gapTimeToNext,
        private int $gapLapsToLead,
        private int $gapLapsToNext,
        private ?int $bestLap,
        private ?float $bestTime,
        private ?bool $bestIsFastest,
        private ?float $bestSpeed,
    ) {
    }

    public function entry(): UuidValueObject
    {
        return new UuidValueObject($this->entry);
    }

    public function finishPosition(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->finishPosition);
    }

    public function gridPosition(): NullableIntValueObject
    {
        return new NullableIntValueObject($this->gridPosition);
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

    public function classifiedStatus(): NullableStringValueObject
    {
        return new NullableStringValueObject($this->classifiedStatus);
    }

    public function averageLapSpeed(): PositiveFloatValueObject
    {
        return new PositiveFloatValueObject($this->averageLapSpeed);
    }

    public function fastestLapTime(): NullableFloatValueObject
    {
        return new NullableFloatValueObject($this->fastestLapTime);
    }

    public function gapTimeToLead(): FloatValueObject
    {
        return new FloatValueObject($this->gapTimeToLead);
    }

    public function gapTimeToNext(): FloatValueObject
    {
        return new FloatValueObject($this->gapTimeToNext);
    }

    public function gapLapsToLead(): IntValueObject
    {
        return new IntValueObject($this->gapLapsToLead);
    }

    public function gapLapsToNext(): IntValueObject
    {
        return new IntValueObject($this->gapLapsToNext);
    }

    public function bestLap(): NullableIntValueObject
    {
        return new NullableIntValueObject($this->bestLap);
    }

    public function bestTime(): NullableFloatValueObject
    {
        return new NullableFloatValueObject($this->bestTime);
    }

    public function bestIsFastest(): NullableBoolValueObject
    {
        return new NullableBoolValueObject($this->bestIsFastest);
    }

    public function bestSpeed(): NullableFloatValueObject
    {
        return new NullableFloatValueObject($this->bestSpeed);
    }

    public static function fromScalars(
        string $entry,
        int $finishPosition,
        ?int $gridPosition,
        int $laps,
        float $points,
        float $time,
        ?string $classifiedStatus,
        float $averageLapSpeed,
        ?float $fastestLapTime,
        float $gapTimeToLead,
        float $gapTimeToNext,
        int $gapLapsToLead,
        int $gapLapsToNext,
        ?int $bestLap,
        ?float $bestTime,
        ?bool $bestIsFastest,
        ?float $bestSpeed,
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
            $bestSpeed,
        );
    }
}
