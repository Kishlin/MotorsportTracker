<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Venue\Application\SearchVenue;

use Kishlin\Backend\MotorsportTracker\Venue\Domain\ValueObject\VenueId;

interface SearchVenueViewer
{
    public function search(string $keyword): ?VenueId;
}
