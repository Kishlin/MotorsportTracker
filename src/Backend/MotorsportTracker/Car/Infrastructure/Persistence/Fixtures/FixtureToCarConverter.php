<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Car\Infrastructure\Persistence\Fixtures;

use Kishlin\Backend\MotorsportTracker\Car\Domain\Entity\Car;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarId;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarNumber;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarSeasonId;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarTeamId;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\Fixture;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureConverter;

final class FixtureToCarConverter implements FixtureConverter
{
    public function convert(Fixture $fixture): AggregateRoot
    {
        return Car::instance(
            new CarId($fixture->identifier()),
            new CarTeamId($fixture->getString('teamId')),
            new CarSeasonId($fixture->getString('seasonId')),
            new CarNumber($fixture->getInt('number')),
        );
    }
}
