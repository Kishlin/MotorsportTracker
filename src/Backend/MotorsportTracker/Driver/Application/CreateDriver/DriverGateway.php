<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriver;

use Kishlin\Backend\MotorsportTracker\Driver\Domain\Entity\Driver;

interface DriverGateway
{
    public function save(Driver $driver): void;
}
