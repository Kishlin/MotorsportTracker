<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Domain\Gateway;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\DriverStanding;

interface DriverStandingGateway
{
    public function save(DriverStanding $driverStanding): void;
}
