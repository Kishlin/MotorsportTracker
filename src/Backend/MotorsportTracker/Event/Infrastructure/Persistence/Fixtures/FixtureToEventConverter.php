<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Fixtures;

use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\Event;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventIndex;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventLabel;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventSeasonId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventVenueId;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\Fixture;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureConverter;

final class FixtureToEventConverter implements FixtureConverter
{
    public function convert(Fixture $fixture): AggregateRoot
    {
        return Event::instance(
            new EventId($fixture->identifier()),
            new EventSeasonId($fixture->getString('seasonId')),
            new EventVenueId($fixture->getString('venueId')),
            new EventIndex($fixture->getInt('index')),
            new EventLabel($fixture->getString('label')),
        );
    }
}
