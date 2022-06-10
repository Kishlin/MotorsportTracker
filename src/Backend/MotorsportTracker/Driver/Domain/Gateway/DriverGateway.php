<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Driver\Domain\Gateway;

use Kishlin\Backend\MotorsportTracker\Driver\Domain\Entity\Driver;

interface DriverGateway
{
    public function save(Driver $driver): void;
}
