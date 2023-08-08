<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Schedule\Application\UpdateSeasonScheduleCache;

interface SeasonScheduleDataGateway
{
    public function findEventsForSeason(string $championship, int $year): SeasonEventListDTO;
}
