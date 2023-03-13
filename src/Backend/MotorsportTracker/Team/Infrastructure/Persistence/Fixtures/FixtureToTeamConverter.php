<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Fixtures;

use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\Team;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\Fixture;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureConverter;

final class FixtureToTeamConverter implements FixtureConverter
{
    public function convert(Fixture $fixture): AggregateRoot
    {
        return Team::instance(
            new UuidValueObject($fixture->identifier()),
            new UuidValueObject($fixture->getString('countryId')),
            new StringValueObject($fixture->getString('name')),
            new NullableStringValueObject($fixture->getString('color')),
            new NullableUuidValueObject(null),
        );
    }
}
