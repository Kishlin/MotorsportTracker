<?php

declare(strict_types=1);

namespace Kishlin\Backend\Country\Infrastructure\Persistence\Fixtures;

use Kishlin\Backend\Country\Domain\Entity\Country;
use Kishlin\Backend\Country\Domain\ValueObject\CountryCode;
use Kishlin\Backend\Country\Domain\ValueObject\CountryId;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\Fixture;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureConverter;

final class FixtureToCountryConverter implements FixtureConverter
{
    public function convert(Fixture $fixture): AggregateRoot
    {
        return Country::instance(
            new CountryId($fixture->identifier()),
            new CountryCode($fixture->value('code')),
        );
    }

    public function handles(string $class): bool
    {
        return 'country' === $class;
    }
}
