<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Standing\Application\ScrapStandings;

use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\SeasonIdentity;
use Kishlin\Backend\MotorsportETL\Standing\Domain\DTO\PossibleStandingClass;
use Kishlin\Backend\MotorsportETL\Standing\Domain\StandingType;

interface StandingsExtractor
{
    public function extract(
        SeasonIdentity $season,
        StandingType $standingTypes,
        ?PossibleStandingClass $possibleStandingClass = null,
    ): string;
}
