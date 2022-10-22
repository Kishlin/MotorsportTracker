<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Venue\Infrastructure\Persistence\Fixtures;

use Kishlin\Backend\MotorsportTracker\Venue\Domain\Entity\Venue;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\ValueObject\VenueCountryId;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\ValueObject\VenueId;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\ValueObject\VenueName;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\Fixture;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureConverter;

final class FixtureToVenueConverter implements FixtureConverter
{
    public function convert(Fixture $fixture): AggregateRoot
    {
        return Venue::instance(
            new VenueId($fixture->identifier()),
            new VenueName($fixture->getString('name')),
            new VenueCountryId($fixture->getString('countryId')),
        );
    }
}
