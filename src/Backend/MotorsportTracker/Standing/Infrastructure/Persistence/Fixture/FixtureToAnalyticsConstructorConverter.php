<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Fixture;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\AnalyticsConstructors;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\FloatValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\Fixture;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureConverter;

final class FixtureToAnalyticsConstructorConverter implements FixtureConverter
{
    public function convert(Fixture $fixture): AggregateRoot
    {
        return AnalyticsConstructors::instance(
            new UuidValueObject($fixture->identifier()),
            new UuidValueObject($fixture->getString('season')),
            new UuidValueObject($fixture->getString('constructor')),
            new UuidValueObject($fixture->getString('country')),
            new PositiveIntValueObject($fixture->getInt('position')),
            new FloatValueObject($fixture->getFloat('points')),
            new PositiveIntValueObject($fixture->getInt('wins')),
        );
    }
}
