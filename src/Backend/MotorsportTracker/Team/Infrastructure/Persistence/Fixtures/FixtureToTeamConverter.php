<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Fixtures;

use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\Team;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamCountryId;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamId;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamImage;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamName;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\Fixture;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureConverter;

final class FixtureToTeamConverter implements FixtureConverter
{
    public function convert(Fixture $fixture): AggregateRoot
    {
        return Team::instance(
            new TeamId($fixture->identifier()),
            new TeamName($fixture->value('name')),
            new TeamImage($fixture->value('image')),
            new TeamCountryId($fixture->value('countryId')),
        );
    }

    public function handles(string $class): bool
    {
        return 'team' === $class;
    }
}
