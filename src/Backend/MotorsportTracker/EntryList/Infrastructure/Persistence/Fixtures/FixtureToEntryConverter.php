<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\EntryList\Infrastructure\Persistence\Fixtures;

use Kishlin\Backend\MotorsportTracker\EntryList\Domain\Entity\Entry;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\Fixture;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureConverter;

final class FixtureToEntryConverter implements FixtureConverter
{
    public function convert(Fixture $fixture): AggregateRoot
    {
        return Entry::instance(
            new UuidValueObject($fixture->identifier()),
            new UuidValueObject($fixture->getString('event')),
            new UuidValueObject($fixture->getString('driver')),
            new NullableUuidValueObject($fixture->getString('team')),
            new StringValueObject($fixture->getString('chassis')),
            new StringValueObject($fixture->getString('engine')),
            new NullableStringValueObject($fixture->getString('seriesName')),
            new NullableStringValueObject($fixture->getString('seriesSlug')),
            new StringValueObject($fixture->getString('carNumber')),
        );
    }
}
