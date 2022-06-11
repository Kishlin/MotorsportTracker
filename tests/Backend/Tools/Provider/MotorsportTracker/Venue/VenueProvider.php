<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Venue;

use Kishlin\Backend\MotorsportTracker\Venue\Domain\Entity\Venue;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\ValueObject\VenueCountryId;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\ValueObject\VenueId;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\ValueObject\VenueName;

final class VenueProvider
{
    public static function dutchVenue(): Venue
    {
        return Venue::instance(
            new VenueId('ce924d04-cc00-4dbd-95ef-1b8b4e1a41f7'),
            new VenueName('Circuit Zandvoort'),
            new VenueCountryId('32bc1722-2ed6-4a81-b1dd-0cf578027b1f'),
        );
    }
}
