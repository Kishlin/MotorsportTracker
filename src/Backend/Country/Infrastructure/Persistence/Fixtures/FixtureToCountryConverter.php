<?php

declare(strict_types=1);

namespace Kishlin\Backend\Country\Infrastructure\Persistence\Fixtures;

use Kishlin\Backend\Country\Domain\Entity\Country;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\Fixture;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureConverter;

final class FixtureToCountryConverter implements FixtureConverter
{
    public function convert(Fixture $fixture): AggregateRoot
    {
        return Country::instance(
            new UuidValueObject($fixture->identifier()),
            new StringValueObject($fixture->getString('code')),
            new StringValueObject($fixture->getString('name')),
            new NullableUuidValueObject(null),
        );
    }
}
