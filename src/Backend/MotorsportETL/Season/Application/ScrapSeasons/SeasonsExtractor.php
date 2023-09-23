<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Season\Application\ScrapSeasons;

use Kishlin\Backend\MotorsportETL\Season\Domain\ValueObject\SeriesIdentity;

interface SeasonsExtractor
{
    public function extract(SeriesIdentity $series): mixed;
}
