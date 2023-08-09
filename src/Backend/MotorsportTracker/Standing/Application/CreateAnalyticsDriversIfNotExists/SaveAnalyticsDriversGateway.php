<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\CreateAnalyticsDriversIfNotExists;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\AnalyticsDrivers;

interface SaveAnalyticsDriversGateway
{
    public function save(AnalyticsDrivers $analytics): void;
}
