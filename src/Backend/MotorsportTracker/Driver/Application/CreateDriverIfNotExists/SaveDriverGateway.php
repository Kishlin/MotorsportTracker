<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriverIfNotExists;

use Kishlin\Backend\MotorsportTracker\Driver\Domain\Entity\Driver;

interface SaveDriverGateway
{
    public function save(Driver $driver): void;
}
