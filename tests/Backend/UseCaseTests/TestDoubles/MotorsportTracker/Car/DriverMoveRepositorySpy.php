<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Car;

use Kishlin\Backend\MotorsportTracker\Car\Application\RecordDriverMove\DriverMoveRecordingFailureException;
use Kishlin\Backend\MotorsportTracker\Car\Domain\Entity\DriverMove;
use Kishlin\Backend\MotorsportTracker\Car\Domain\Gateway\DriverMoveGateway;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\DriverMoveId;
use Kishlin\Backend\MotorsportTracker\Racer\Application\UpdateRacerViewsOnDriverMove\DriverMoveData;
use Kishlin\Backend\MotorsportTracker\Racer\Application\UpdateRacerViewsOnDriverMove\DriverMoveDataGateway;
use Kishlin\Backend\MotorsportTracker\Racer\Application\UpdateRacerViewsOnDriverMove\DriverMoveDataNotFoundException;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Driver\DriverRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property DriverMove[] $objects
 *
 * @method DriverMove[]    all()
 * @method null|DriverMove get(DriverMoveId $id)
 */
final class DriverMoveRepositorySpy extends AbstractRepositorySpy implements DriverMoveGateway, DriverMoveDataGateway
{
    public function __construct(
        private CarRepositorySpy $carRepositorySpy,
        private DriverRepositorySpy $driverRepositorySpy,
    ) {
    }

    public function save(DriverMove $driverMove): void
    {
        if ($this->driverAlreadyMovedOnDate($driverMove)
            || $this->carAlreadyChangedDriverOnDate($driverMove)
            || false === $this->carRepositorySpy->has($driverMove->carId())
            || false === $this->driverRepositorySpy->has($driverMove->driverId())) {
            throw new DriverMoveRecordingFailureException();
        }

        $this->objects[$driverMove->id()->value()] = $driverMove;
    }

    public function find(UuidValueObject $driverMoveId): DriverMoveData
    {
        foreach ($this->objects as $driverMove) {
            if ($driverMove->id()->equals($driverMoveId)) {
                return DriverMoveData::fromScalars(
                    $driverMove->driverId()->value(),
                    $driverMove->carId()->value(),
                    $driverMove->date()->value(),
                );
            }
        }

        throw new DriverMoveDataNotFoundException();
    }

    private function driverAlreadyMovedOnDate(DriverMove $driverMove): bool
    {
        foreach ($this->objects as $savedDriverMove) {
            if ($savedDriverMove->date()->equals($driverMove->date())
                && $savedDriverMove->driverId()->equals($driverMove->driverId())) {
                return true;
            }
        }

        return false;
    }

    private function carAlreadyChangedDriverOnDate(DriverMove $driverMove): bool
    {
        foreach ($this->objects as $savedDriverMove) {
            if ($savedDriverMove->date()->equals($driverMove->date())
                && $savedDriverMove->carId()->equals($driverMove->carId())) {
                return true;
            }
        }

        return false;
    }
}
