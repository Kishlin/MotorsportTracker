<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\CreateAnalyticsIfNotExists;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\DTO\AnalyticsStatsDTO;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\FloatValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class CreateAnalyticsIfNotExistsCommand implements Command
{
    private function __construct(
        private readonly string $season,
        private readonly string $driver,
        private readonly int $position,
        private readonly float $points,
        private readonly AnalyticsStatsDTO $analyticsStatsDTO,
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
        int $position,
        float $points,
        AnalyticsStatsDTO $analyticsStatsDTO,
    ): self {
        return new self($season, $driver, $position, $points, $analyticsStatsDTO);
    }
}
