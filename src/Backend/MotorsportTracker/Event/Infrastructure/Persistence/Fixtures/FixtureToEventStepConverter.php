<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Fixtures;

use Exception;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\EventStep;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepDateTime;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepEventId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepTypeId;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\Fixture;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureConverter;

final class FixtureToEventStepConverter implements FixtureConverter
{
    /**
     * @throws Exception
     */
    public function convert(Fixture $fixture): AggregateRoot
    {
        return EventStep::instance(
            new EventStepId($fixture->identifier()),
            new EventStepTypeId($fixture->getString('stepTypeId')),
            new EventStepEventId($fixture->getString('eventId')),
            new EventStepDateTime($fixture->getDateTime('dateTime')),
        );
    }
}
