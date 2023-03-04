<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\SearchSeason;

use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface SearchSeasonViewer
{
    public function search(string $championship, int $year): ?UuidValueObject;
}
