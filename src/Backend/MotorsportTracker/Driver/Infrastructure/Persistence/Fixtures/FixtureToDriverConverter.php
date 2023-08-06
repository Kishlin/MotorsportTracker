<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Driver\Infrastructure\Persistence\Fixtures;

use Kishlin\Backend\MotorsportTracker\Driver\Domain\Entity\Driver;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\Fixture;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureConverter;

final class FixtureToDriverConverter implements FixtureConverter
{
    public function convert(Fixture $fixture): AggregateRoot
    {
        return Driver::instance(
            new UuidValueObject($fixture->identifier()),
            new StringValueObject($fixture->getString('name')),
            new StringValueObject($fixture->getString('shortCode')),
            new NullableUuidValueObject(null),
        );
    }
}
