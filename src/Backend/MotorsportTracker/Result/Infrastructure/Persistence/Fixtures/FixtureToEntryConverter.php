<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Fixtures;

use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\Entry;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\Fixture;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureConverter;

final class FixtureToEntryConverter implements FixtureConverter
{
    public function convert(Fixture $fixture): AggregateRoot
    {
        return Entry::instance(
            new UuidValueObject($fixture->identifier()),
            new UuidValueObject($fixture->getString('session')),
            new NullableUuidValueObject($fixture->getString('country')),
            new UuidValueObject($fixture->getString('driver')),
            new UuidValueObject($fixture->getString('team')),
            new PositiveIntValueObject($fixture->getInt('carNumber')),
        );
    }
}
