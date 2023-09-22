<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Series\Application\ScrapSeriesList;

interface SeriesListExtractor
{
    public function extract(): mixed;
}
