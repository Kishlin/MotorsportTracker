<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\CreateAnalyticsTeamsIfNotExists;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\DTO\AnalyticsTeamsStatsDTO;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\FloatValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final readonly class CreateAnalyticsTeamsIfNotExistsCommand implements Command
{
    private function __construct(
        private string $season,
        private string $team,
        private ?string $country,
        private int $position,
        private float $points,
        private AnalyticsTeamsStatsDTO $analyticsStatsDTO,
    ) {
    }

    public function season(): UuidValueObject
    {
        return new UuidValueObject($this->season);
    }

    public function team(): UuidValueObject
    {
        return new UuidValueObject($this->team);
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

    public function analyticsStatsDTO(): AnalyticsTeamsStatsDTO
    {
        return $this->analyticsStatsDTO;
    }

    public static function fromScalars(
        string $season,
        string $team,
        ?string $country,
        int $position,
        float $points,
        AnalyticsTeamsStatsDTO $analyticsStatsDTO,
    ): self {
        return new self($season, $team, $country, $position, $points, $analyticsStatsDTO);
    }
}
