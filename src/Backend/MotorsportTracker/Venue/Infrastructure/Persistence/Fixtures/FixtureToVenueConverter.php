<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Venue\Infrastructure\Persistence\Fixtures;

use Kishlin\Backend\MotorsportTracker\Venue\Domain\Entity\Venue;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\Fixture;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureConverter;

final class FixtureToVenueConverter implements FixtureConverter
{
    public function convert(Fixture $fixture): AggregateRoot
    {
        return Venue::instance(
            new UuidValueObject($fixture->identifier()),
            new StringValueObject($fixture->getString('name')),
            new StringValueObject($fixture->getString('slug')),
            new UuidValueObject($fixture->getString('countryId')),
        );
    }
}
