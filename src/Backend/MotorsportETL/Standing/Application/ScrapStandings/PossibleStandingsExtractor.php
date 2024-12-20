<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Standing\Application\ScrapStandings;

use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\SeasonIdentity;

interface PossibleStandingsExtractor
{
    public function extract(SeasonIdentity $season): string;
}
