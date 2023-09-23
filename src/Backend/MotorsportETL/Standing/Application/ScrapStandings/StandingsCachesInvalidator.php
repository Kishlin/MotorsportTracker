<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Standing\Application\ScrapStandings;

use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\SeasonIdentity;

interface StandingsCachesInvalidator
{
    public function invalidate(SeasonIdentity $season): void;
}
