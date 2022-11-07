<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\SearchSeason;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\SeasonId;

interface SearchSeasonViewer
{
    public function search(string $championship, int $year): ?SeasonId;
}
