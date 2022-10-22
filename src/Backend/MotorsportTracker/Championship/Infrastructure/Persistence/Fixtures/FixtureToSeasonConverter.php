<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Fixtures;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Season;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\SeasonChampionshipId;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\SeasonId;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\SeasonYear;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\Fixture;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureConverter;

final class FixtureToSeasonConverter implements FixtureConverter
{
    public function convert(Fixture $fixture): AggregateRoot
    {
        return Season::instance(
            new SeasonId($fixture->identifier()),
            new SeasonYear($fixture->getInt('year')),
            new SeasonChampionshipId($fixture->getString('championshipId')),
        );
    }
}
