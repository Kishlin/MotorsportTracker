<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\RefreshStandingsOnResultsRecorded;

final class StandingDataDTO
{
    private function __construct(
        private string $driverId,
        private string $teamId,
        private float $pointsUntilEvent,
    ) {
    }

    public function driverId(): string
    {
        return $this->driverId;
    }

    public function teamId(): string
    {
        return $this->teamId;
    }

    public function pointsUntilEvent(): float
    {
        return $this->pointsUntilEvent;
    }

    public static function fromScalars(
        string $driverId,
        string $teamId,
        float $pointsUntilEvent,
    ): self {
        return new self($driverId, $teamId, $pointsUntilEvent);
    }
}
