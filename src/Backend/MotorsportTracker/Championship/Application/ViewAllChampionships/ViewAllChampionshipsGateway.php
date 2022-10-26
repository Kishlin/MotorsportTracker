<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\ViewAllChampionships;

interface ViewAllChampionshipsGateway
{
    /**
     * @return ChampionshipPOPO[]
     */
    public function viewAllChampionships(): array;
}
