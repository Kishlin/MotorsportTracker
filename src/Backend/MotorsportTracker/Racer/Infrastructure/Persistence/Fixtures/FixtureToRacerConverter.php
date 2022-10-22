<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Racer\Infrastructure\Persistence\Fixtures;

use DateTimeImmutable;
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
            new RacerDriverId($fixture->value('driverId')),
            new RacerCarId($fixture->value('carId')),
            new RacerStartDate(new DateTimeImmutable($fixture->value('startDate'))),
            new RacerEndDate(new DateTimeImmutable($fixture->value('endDate'))),
        );
    }

    public function handles(string $class): bool
    {
        return 'racer' === $class;
    }
}
