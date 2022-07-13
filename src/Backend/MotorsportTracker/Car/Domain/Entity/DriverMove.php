<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Car\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Car\Domain\DomainEvent\DriverMoveCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\DriverMoveCarId;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\DriverMoveDate;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\DriverMoveDriverId;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\DriverMoveId;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;

final class DriverMove extends AggregateRoot
{
    private function __construct(
        private DriverMoveId $id,
        private DriverMoveDriverId $driverId,
        private DriverMoveCarId $carId,
        private DriverMoveDate $date,
    ) {
    }

    public static function create(
        DriverMoveId $id,
        DriverMoveDriverId $driverId,
        DriverMoveCarId $carId,
        DriverMoveDate $date,
    ): self {
        $driverMove = new self($id, $driverId, $carId, $date);

        $driverMove->record(new DriverMoveCreatedDomainEvent($id));

        return $driverMove;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        DriverMoveId $id,
        DriverMoveDriverId $driverId,
        DriverMoveCarId $carId,
        DriverMoveDate $date,
    ): self {
        return new self($id, $driverId, $carId, $date);
    }

    public function id(): DriverMoveId
    {
        return $this->id;
    }

    public function driverId(): DriverMoveDriverId
    {
        return $this->driverId;
    }

    public function carId(): DriverMoveCarId
    {
        return $this->carId;
    }

    public function date(): DriverMoveDate
    {
        return $this->date;
    }
}
