<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Application\ComputeSeasonStandings\Gateway;

use Kishlin\Backend\MotorsportCache\Standing\Application\ComputeSeasonStandings\StandingsDataDTO;

interface StandingsDataGateway
{
    public function findForSeason(string $championship, int $year): StandingsDataDTO;
}
