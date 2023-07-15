<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapStandings\Gateway;

use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapStandings\DTO\StandingsConstructorDTO;

interface StandingConstructorGateway
{
    public function fetch(string $seasonRef, ?string $seriesClassUuid = null): StandingsConstructorDTO;
}
