<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Analytics\Application\UpdateDriverAnalyticsCache;

interface DriverAnalyticsForSeasonGateway
{
    public function find(string $championship, int $year): DriverAnalyticsForSeasonDTO;
}
