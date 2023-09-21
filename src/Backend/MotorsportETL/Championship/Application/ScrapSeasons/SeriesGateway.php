<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Championship\Application\ScrapSeasons;

interface SeriesGateway
{
    public function find(string $seriesName): ?SeriesDTO;
}
