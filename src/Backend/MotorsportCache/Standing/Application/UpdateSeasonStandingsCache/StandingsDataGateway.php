<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Application\UpdateSeasonStandingsCache;

interface StandingsDataGateway
{
    public function findForSeason(string $championship, int $year): StandingsDataDTO;
}
