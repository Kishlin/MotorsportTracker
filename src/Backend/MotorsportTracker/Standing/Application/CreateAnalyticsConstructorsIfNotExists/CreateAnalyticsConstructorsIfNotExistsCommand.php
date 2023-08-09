<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\CreateAnalyticsConstructorsIfNotExists;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\DTO\AnalyticsConstructorsStatsDTO;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\FloatValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final readonly class CreateAnalyticsConstructorsIfNotExistsCommand implements Command
{
    private function __construct(
        private string $season,
        private string $constructor,
        private ?string $country,
        private int $position,
        private float $points,
        private AnalyticsConstructorsStatsDTO $analyticsStatsDTO,
    ) {
    }

    public function season(): UuidValueObject
    {
        return new UuidValueObject($this->season);
    }

    public function constructor(): UuidValueObject
    {
        return new UuidValueObject($this->constructor);
    }

    public function country(): NullableUuidValueObject
    {
        return new NullableUuidValueObject($this->country);
    }

    public function position(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->position);
    }

    public function points(): FloatValueObject
    {
        return new FloatValueObject($this->points);
    }

    public function analyticsStatsDTO(): AnalyticsConstructorsStatsDTO
    {
        return $this->analyticsStatsDTO;
    }

    public static function fromScalars(
        string $season,
        string $constructor,
        ?string $country,
        int $position,
        float $points,
        AnalyticsConstructorsStatsDTO $analyticsStatsDTO,
    ): self {
        return new self($season, $constructor, $country, $position, $points, $analyticsStatsDTO);
    }
}
