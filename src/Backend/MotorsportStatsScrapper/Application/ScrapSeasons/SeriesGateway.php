<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapSeasons;

interface SeriesGateway
{
    public function findMotorsportStatsUuidForName(string $name): ?SeriesDTO;
}
