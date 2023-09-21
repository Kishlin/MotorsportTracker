<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Championship\Application\ScrapSeasons;

interface SeasonsExtractor
{
    public function extract(SeriesDTO $series): mixed;
}
