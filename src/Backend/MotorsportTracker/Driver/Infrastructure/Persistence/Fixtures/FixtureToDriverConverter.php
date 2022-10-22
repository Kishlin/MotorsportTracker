<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Driver\Infrastructure\Persistence\Fixtures;

use Kishlin\Backend\MotorsportTracker\Driver\Domain\Entity\Driver;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\ValueObject\DriverCountryId;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\ValueObject\DriverFirstname;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\ValueObject\DriverId;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\ValueObject\DriverName;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\Fixture;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureConverter;

final class FixtureToDriverConverter implements FixtureConverter
{
    public function convert(Fixture $fixture): AggregateRoot
    {
        return Driver::instance(
            new DriverId($fixture->identifier()),
            new DriverFirstname($fixture->value('firstname')),
            new DriverName($fixture->value('name')),
            new DriverCountryId($fixture->value('countryId')),
        );
    }

    public function handles(string $class): bool
    {
        return 'driver' === $class;
    }
}
