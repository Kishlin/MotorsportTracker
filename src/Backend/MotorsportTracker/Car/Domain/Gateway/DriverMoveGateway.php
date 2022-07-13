<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Car\Domain\Gateway;

use Kishlin\Backend\MotorsportTracker\Car\Domain\Entity\DriverMove;

interface DriverMoveGateway
{
    public function save(DriverMove $driverMove): void;
}
