<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Season\Application\ScrapSeasons;

interface SeasonsCacheInvalidator
{
    public function invalidate(SeriesDTO $seriesDTO): void;
}
