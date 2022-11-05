<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Fixtures;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\DriverStanding;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingDriverId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingEventId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingPoints;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\Fixture;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureConverter;

final class FixtureToDriverStandingConverter implements FixtureConverter
{
    public function convert(Fixture $fixture): AggregateRoot
    {
        return DriverStanding::instance(
            new DriverStandingId($fixture->identifier()),
            new DriverStandingEventId($fixture->getString('eventId')),
            new DriverStandingDriverId($fixture->getString('driverId')),
            new DriverStandingPoints($fixture->getFloat('points')),
        );
    }
}
