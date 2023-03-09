<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\SyncChampionship;

use Kishlin\Backend\MotorsportStatsScrapper\Domain\Entity\Championship;

interface ChampionshipGateway
{
    public function fetch(string $seriesSlug, int $year): Championship;
}
