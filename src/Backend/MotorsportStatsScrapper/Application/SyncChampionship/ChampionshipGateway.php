<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\SyncChampionship;

interface ChampionshipGateway
{
    public function fetch(string $seriesSlug, int $year): SyncChampionshipHTTPResponse;
}
