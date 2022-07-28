<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Car;

use Kishlin\Backend\MotorsportTracker\Car\Application\RecordDriverMove\DriverMoveRecordingFailureException;
use Kishlin\Backend\MotorsportTracker\Car\Domain\Entity\DriverMove;
use Kishlin\Backend\MotorsportTracker\Car\Domain\Gateway\DriverMoveGateway;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\DriverMoveId;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Driver\DriverRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property DriverMove[] $objects
 *
 * @method DriverMove get(DriverMoveId $id)
 */
final class DriverMoveRepositorySpy extends AbstractRepositorySpy implements DriverMoveGateway
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
