<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Application\ComputeSeasonStandings\Gateway;

use Kishlin\Backend\MotorsportCache\Standing\Domain\Entity\Standings;

interface SaveStandingsGateway
{
    public function save(Standings $standingsView): void;
}
