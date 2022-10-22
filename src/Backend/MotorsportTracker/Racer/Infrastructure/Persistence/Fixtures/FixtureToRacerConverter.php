<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Racer\Infrastructure\Persistence\Fixtures;

use Exception;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\Entity\Racer;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerCarId;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerDriverId;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerEndDate;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerId;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerStartDate;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\Fixture;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureConverter;

final class FixtureToRacerConverter implements FixtureConverter
{
    /**
     * @throws Exception
     */
    public function convert(Fixture $fixture): AggregateRoot
    {
        return Racer::instance(
            new RacerId($fixture->identifier()),
            new RacerDriverId($fixture->getString('driverId')),
            new RacerCarId($fixture->getString('carId')),
            new RacerStartDate($fixture->getDateTime('startDate')),
            new RacerEndDate($fixture->getDateTime('endDate')),
        );
    }
}
