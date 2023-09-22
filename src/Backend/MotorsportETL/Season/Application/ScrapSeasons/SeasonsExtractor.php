<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Season\Application\ScrapSeasons;

interface SeasonsExtractor
{
    public function extract(SeriesDTO $series): mixed;
}
