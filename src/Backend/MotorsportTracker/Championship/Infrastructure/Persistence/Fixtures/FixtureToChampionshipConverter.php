<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Fixtures;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Championship;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipId;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipName;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipSlug;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\Fixture;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureConverter;

final class FixtureToChampionshipConverter implements FixtureConverter
{
    public function convert(Fixture $fixture): AggregateRoot
    {
        return Championship::instance(
            new ChampionshipId($fixture->identifier()),
            new ChampionshipName($fixture->getString('name')),
            new ChampionshipSlug($fixture->getString('slug')),
        );
    }
}
