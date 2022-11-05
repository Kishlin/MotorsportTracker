<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\RefreshDriverStandingsOnResultsRecorded;

final class StandingDataDTO
{
    private function __construct(
        private string $driverId,
        private string $teamId,
        private float $pointsTotalUntilPreviousEvent,
        private float $pointsForEvent,
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

    public function pointsTotalUntilPreviousEvent(): float
    {
        return $this->pointsTotalUntilPreviousEvent;
    }

    public function pointsForEvent(): float
    {
        return $this->pointsForEvent;
    }

    public static function fromScalars(
        string $driverId,
        string $teamId,
        float $pointsTotalUntilPreviousEvent,
        float $pointsForEvent,
    ): self {
        return new self($driverId, $teamId, $pointsTotalUntilPreviousEvent, $pointsForEvent);
    }
}
