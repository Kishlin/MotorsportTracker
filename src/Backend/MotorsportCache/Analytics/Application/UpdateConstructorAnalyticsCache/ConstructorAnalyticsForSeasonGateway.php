<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Analytics\Application\UpdateConstructorAnalyticsCache;

use Kishlin\Backend\MotorsportCache\Analytics\Domain\DTO\AnalyticsForSeasonDTO;

interface ConstructorAnalyticsForSeasonGateway
{
    public function find(string $championship, int $year): AnalyticsForSeasonDTO;
}
