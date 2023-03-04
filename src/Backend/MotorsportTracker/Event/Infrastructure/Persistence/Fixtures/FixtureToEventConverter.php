<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Fixtures;

use Exception;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\Event;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableDateTimeValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\Fixture;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureConverter;

final class FixtureToEventConverter implements FixtureConverter
{
    /**
     * @throws Exception
     */
    public function convert(Fixture $fixture): AggregateRoot
    {
        return Event::instance(
            new UuidValueObject($fixture->identifier()),
            new UuidValueObject($fixture->getString('seasonId')),
            new UuidValueObject($fixture->getString('venueId')),
            new PositiveIntValueObject($fixture->getInt('index')),
            new StringValueObject($fixture->getString('slug')),
            new StringValueObject($fixture->getString('name')),
            new NullableStringValueObject($fixture->getString('shortName')),
            new NullableDateTimeValueObject($fixture->getDateTime('startTime')),
            new NullableDateTimeValueObject($fixture->getDateTime('endTime')),
        );
    }
}
