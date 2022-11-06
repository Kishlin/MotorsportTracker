<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingDriverId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingEventId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingPoints;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;

final class DriverStanding extends AggregateRoot
{
    private function __construct(
        private DriverStandingId $id,
        private DriverStandingEventId $eventId,
        private DriverStandingDriverId $driverId,
        private DriverStandingPoints $points,
    ) {
    }

    public static function create(
        DriverStandingId $id,
        DriverStandingEventId $eventId,
        DriverStandingDriverId $driverId,
        DriverStandingPoints $points,
    ): self {
        return new self($id, $eventId, $driverId, $points);
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        DriverStandingId $id,
        DriverStandingEventId $eventId,
        DriverStandingDriverId $driverId,
        DriverStandingPoints $points,
    ): self {
        return new self($id, $eventId, $driverId, $points);
    }

    public function updateScore(DriverStandingPoints $newDriverTotal): void
    {
        $this->points = $newDriverTotal;
    }

    public function id(): DriverStandingId
    {
        return $this->id;
    }

    public function eventId(): DriverStandingEventId
    {
        return $this->eventId;
    }

    public function driverId(): DriverStandingDriverId
    {
        return $this->driverId;
    }

    public function points(): DriverStandingPoints
    {
        return $this->points;
    }
}
