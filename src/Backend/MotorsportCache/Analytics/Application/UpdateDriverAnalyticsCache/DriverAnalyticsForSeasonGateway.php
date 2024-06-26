<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Analytics\Application\UpdateDriverAnalyticsCache;

use Kishlin\Backend\MotorsportCache\Analytics\Domain\DTO\AnalyticsForSeasonDTO;

interface DriverAnalyticsForSeasonGateway
{
    public function find(string $championship, int $year): AnalyticsForSeasonDTO;
}
