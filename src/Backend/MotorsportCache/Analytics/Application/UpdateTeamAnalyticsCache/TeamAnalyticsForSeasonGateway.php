<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Analytics\Application\UpdateTeamAnalyticsCache;

use Kishlin\Backend\MotorsportCache\Analytics\Domain\DTO\AnalyticsForSeasonDTO;

interface TeamAnalyticsForSeasonGateway
{
    public function find(string $championship, int $year): AnalyticsForSeasonDTO;
}
