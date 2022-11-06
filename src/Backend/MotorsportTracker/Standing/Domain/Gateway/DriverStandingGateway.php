<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Domain\Gateway;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\DriverStanding;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingDriverId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingEventId;

interface DriverStandingGateway
{
    public function save(DriverStanding $driverStanding): void;

    public function find(DriverStandingDriverId $driverId, DriverStandingEventId $eventId): ?DriverStanding;
}
