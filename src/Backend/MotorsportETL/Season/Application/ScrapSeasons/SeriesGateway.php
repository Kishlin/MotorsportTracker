<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Season\Application\ScrapSeasons;

interface SeriesGateway
{
    public function find(string $seriesName): ?SeriesDTO;
}
