<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Fixtures;

use Exception;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\EventSession;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableDateTimeValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\Fixture;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureConverter;

final class FixtureToEventSessionConverter implements FixtureConverter
{
    /**
     * @throws Exception
     */
    public function convert(Fixture $fixture): AggregateRoot
    {
        return EventSession::instance(
            new UuidValueObject($fixture->identifier()),
            new UuidValueObject($fixture->getString('eventId')),
            new UuidValueObject($fixture->getString('sessionTypeId')),
            new BoolValueObject($fixture->getBool('hasResult')),
            new NullableDateTimeValueObject($fixture->getDateTime('startDate')),
            new NullableDateTimeValueObject($fixture->getDateTime('endDate')),
            new NullableUuidValueObject(null),
        );
    }
}
