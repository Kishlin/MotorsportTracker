<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Venue\Application\SearchVenue;

use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface SearchVenueViewer
{
    public function search(string $slug): ?UuidValueObject;
}
