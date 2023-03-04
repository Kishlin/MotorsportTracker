<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Fixtures;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Season;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\Fixture;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureConverter;

final class FixtureToSeasonConverter implements FixtureConverter
{
    public function convert(Fixture $fixture): AggregateRoot
    {
        return Season::instance(
            new UuidValueObject($fixture->identifier()),
            new StrictlyPositiveIntValueObject($fixture->getInt('year')),
            new UuidValueObject($fixture->getString('championshipId')),
        );
    }
}
