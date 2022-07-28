<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Car\Application\RecordDriverMove;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\DriverMoveCarId;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\DriverMoveDate;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\DriverMoveDriverId;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final class RecordDriverMoveCommand implements Command
{
    private function __construct(
        private string $carId,
        private string $driverId,
        private DateTimeImmutable $date,
    ) {
    }

    public function carId(): DriverMoveCarId
    {
        return new DriverMoveCarId($this->carId);
    }

    public function driverId(): DriverMoveDriverId
    {
        return new DriverMoveDriverId($this->driverId);
    }

    public function date(): DriverMoveDate
    {
        return new DriverMoveDate($this->date);
    }

    public static function fromScalars(string $carId, string $driverId, DateTimeImmutable $date): self
    {
        return new self($carId, $driverId, $date);
    }
}
