<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapStandings\Gateway;

use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapStandings\DTO\StandingsDataDTO;

interface StandingsGateway
{
    public function fetchStandingsDataForSeason(string $seasonRef): StandingsDataDTO;
}
