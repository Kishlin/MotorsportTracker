<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\CreateAnalyticsDriversIfNotExists;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\DTO\AnalyticsStatsDTO;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\FloatValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final readonly class CreateAnalyticsDriversIfNotExistsCommand implements Command
{
    private function __construct(
        private string $season,
        private string $driver,
        private string $country,
        private int $position,
        private float $points,
        private AnalyticsStatsDTO $analyticsStatsDTO,
    ) {
    }

    public function season(): UuidValueObject
    {
        return new UuidValueObject($this->season);
    }

    public function driver(): UuidValueObject
    {
        return new UuidValueObject($this->driver);
    }

    public function country(): UuidValueObject
    {
        return new UuidValueObject($this->country);
    }

    public function position(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->position);
    }

    public function points(): FloatValueObject
    {
        return new FloatValueObject($this->points);
    }

    public function analyticsStatsDTO(): AnalyticsStatsDTO
    {
        return $this->analyticsStatsDTO;
    }

    public static function fromScalars(
        string $season,
        string $driver,
        string $country,
        int $position,
        float $points,
        AnalyticsStatsDTO $analyticsStatsDTO,
    ): self {
        return new self($season, $driver, $country, $position, $points, $analyticsStatsDTO);
    }
}
