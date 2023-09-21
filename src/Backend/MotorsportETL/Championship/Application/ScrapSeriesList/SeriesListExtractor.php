<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Championship\Application\ScrapSeriesList;

interface SeriesListExtractor
{
    public function extract(): mixed;
}
