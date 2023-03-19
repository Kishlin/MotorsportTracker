<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Fixture;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\Analytics;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\FloatValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\Fixture;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureConverter;

final class FixtureToAnalyticsConverter implements FixtureConverter
{
    public function convert(Fixture $fixture): AggregateRoot
    {
        return Analytics::instance(
            new UuidValueObject($fixture->identifier()),
            new UuidValueObject($fixture->getString('season')),
            new UuidValueObject($fixture->getString('driver')),
            new PositiveIntValueObject($fixture->getInt('position')),
            new FloatValueObject($fixture->getFloat('points')),
            new FloatValueObject($fixture->getFloat('avgFinishPosition')),
            new PositiveIntValueObject($fixture->getInt('classWins')),
            new PositiveIntValueObject($fixture->getInt('fastestLaps')),
            new PositiveIntValueObject($fixture->getInt('finalAppearances')),
            new PositiveIntValueObject($fixture->getInt('hatTricks')),
            new PositiveIntValueObject($fixture->getInt('podiums')),
            new PositiveIntValueObject($fixture->getInt('poles')),
            new PositiveIntValueObject($fixture->getInt('racesLed')),
            new PositiveIntValueObject($fixture->getInt('ralliesLed')),
            new PositiveIntValueObject($fixture->getInt('retirements')),
            new PositiveIntValueObject($fixture->getInt('semiFinalAppearances')),
            new PositiveIntValueObject($fixture->getInt('stageWins')),
            new PositiveIntValueObject($fixture->getInt('starts')),
            new PositiveIntValueObject($fixture->getInt('top10s')),
            new PositiveIntValueObject($fixture->getInt('top5s')),
            new PositiveIntValueObject($fixture->getInt('wins')),
            new FloatValueObject($fixture->getFloat('winsPercentage')),
        );
    }
}
