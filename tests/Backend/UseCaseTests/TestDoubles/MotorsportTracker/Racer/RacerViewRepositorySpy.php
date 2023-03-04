<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Racer;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportTracker\Racer\Application\GetAllRacersForDateTime\RacersForDateTimeAndSeasonGateway;
use Kishlin\Backend\MotorsportTracker\Racer\Application\GetAllRacersForDateTime\SeasonId as RacerSeasonId;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\Entity\Racer;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\View\RacerPOPO;
use Kishlin\Tests\Backend\Tools\ReflectionHelper;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Car\CarRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Driver\DriverRepositorySpy;
use ReflectionException;

final class RacerViewRepositorySpy implements RacersForDateTimeAndSeasonGateway
{
    public function __construct(
        private CarRepositorySpy $carRepositorySpy,
        private RacerRepositorySpy $racerRepositorySpy,
        private DriverRepositorySpy $driverRepositorySpy,
    ) {
    }

    /**
     * @throws ReflectionException
     */
    public function findRacersForDateTimeAndSeason(DateTimeImmutable $dateTime, RacerSeasonId $seasonId): array
    {
        $out = [];

        /** @var Racer[] $racers */
        $racers = ReflectionHelper::propertyValue($this->racerRepositorySpy, 'objects');

        foreach ($racers as $racer) {
            if ($racer->startDate()->value() > $dateTime || $racer->endDate()->value() < $dateTime) {
                continue;
            }

            $car = $this->carRepositorySpy->safeGet($racer->carId());

            if (false === $car->seasonId()->equals($seasonId)) {
                continue;
            }

            $driver = $this->driverRepositorySpy->safeGet($racer->driverId());

            $out[] = RacerPOPO::fromScalars(
                $racer->id()->value(),
                $driver->name()->value(),
                $car->number()->value(),
            );
        }

        return $out;
    }
}
