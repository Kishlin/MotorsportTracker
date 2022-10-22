<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Car\Infrastructure\Persistence\Fixtures;

use Exception;
use Kishlin\Backend\MotorsportTracker\Car\Domain\Entity\DriverMove;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\DriverMoveCarId;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\DriverMoveDate;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\DriverMoveDriverId;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\DriverMoveId;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\Fixture;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureConverter;

final class FixtureToDriverMoveConverter implements FixtureConverter
{
    /**
     * @throws Exception
     */
    public function convert(Fixture $fixture): AggregateRoot
    {
        return DriverMove::instance(
            new DriverMoveId($fixture->identifier()),
            new DriverMoveDriverId($fixture->getString('driverId')),
            new DriverMoveCarId($fixture->getString('carId')),
            new DriverMoveDate($fixture->getDateTime('date'))
        );
    }
}
