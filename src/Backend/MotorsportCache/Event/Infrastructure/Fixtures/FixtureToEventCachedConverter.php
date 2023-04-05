<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Event\Infrastructure\Fixtures;

use Kishlin\Backend\MotorsportCache\Event\Domain\Entity\EventCached;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\Fixture;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureConverter;

final class FixtureToEventCachedConverter implements FixtureConverter
{
    public function convert(Fixture $fixture): AggregateRoot
    {
        return EventCached::instance(
            new UuidValueObject($fixture->identifier()),
            new StringValueObject($fixture->getString('championship')),
            new StrictlyPositiveIntValueObject($fixture->getInt('year')),
            new StringValueObject($fixture->getString('eventSlug')),
        );
    }
}
