<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Driver\Application\SearchDriver;

use Kishlin\Backend\MotorsportTracker\Driver\Domain\ValueObject\DriverId;

interface SearchDriverViewer
{
    public function search(string $name): ?DriverId;
}
