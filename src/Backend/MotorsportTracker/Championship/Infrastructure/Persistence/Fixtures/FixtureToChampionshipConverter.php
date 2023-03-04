<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Fixtures;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Championship;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\Fixture;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureConverter;

final class FixtureToChampionshipConverter implements FixtureConverter
{
    public function convert(Fixture $fixture): AggregateRoot
    {
        return Championship::instance(
            new UuidValueObject($fixture->identifier()),
            new StringValueObject($fixture->getString('name')),
            new StringValueObject($fixture->getString('slug')),
        );
    }
}
