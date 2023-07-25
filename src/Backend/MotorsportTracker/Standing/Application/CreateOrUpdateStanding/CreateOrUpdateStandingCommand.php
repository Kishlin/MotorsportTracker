<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\CreateOrUpdateStanding;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Enum\StandingType;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\FloatValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final readonly class CreateOrUpdateStandingCommand implements Command
{
    private function __construct(
        private string $season,
        private ?string $seriesClass,
        private string $standee,
        private int $position,
        private float $points,
        private StandingType $standingType,
    ) {
    }

    public function season(): UuidValueObject
    {
        return new UuidValueObject($this->season);
    }

    public function seriesClass(): NullableStringValueObject
    {
        return new NullableStringValueObject($this->seriesClass);
    }

    public function standee(): UuidValueObject
    {
        return new UuidValueObject($this->standee);
    }

    public function position(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->position);
    }

    public function points(): FloatValueObject
    {
        return new FloatValueObject($this->points);
    }

    public function standingType(): StandingType
    {
        return $this->standingType;
    }

    public static function fromScalars(
        string $season,
        ?string $seriesClass,
        string $standee,
        int $position,
        float $points,
        StandingType $standingType,
    ): self {
        return new self($season, $seriesClass, $standee, $position, $points, $standingType);
    }
}
