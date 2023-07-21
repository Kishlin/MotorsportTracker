<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Infrastructure\Persistence\Repository\ComputeSeasonStandings;

use Kishlin\Backend\MotorsportCache\Standing\Application\ComputeSeasonStandings\Gateway\SaveStandingsGateway;
use Kishlin\Backend\MotorsportCache\Standing\Domain\Entity\Standings;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CacheRepository;

final class SaveStandingsRepository extends CacheRepository implements SaveStandingsGateway
{
    public function save(Standings $standingsView): void
    {
        $this->persist($standingsView);
    }
}
