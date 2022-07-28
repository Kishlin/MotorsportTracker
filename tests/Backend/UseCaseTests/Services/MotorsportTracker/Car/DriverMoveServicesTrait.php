<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Car;

use Kishlin\Backend\MotorsportTracker\Car\Application\RecordDriverMove\RecordDriverMoveCommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Car\CarRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Car\DriverMoveRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Driver\DriverRepositorySpy;

trait DriverMoveServicesTrait
{
    private ?DriverMoveRepositorySpy $driverMoveRepositorySpy = null;

    private ?RecordDriverMoveCommandHandler $recordDriverMoveCommandHandler = null;

    abstract public function eventDispatcher(): EventDispatcher;

    abstract public function uuidGenerator(): UuidGenerator;

    abstract public function carRepositorySpy(): CarRepositorySpy;

    abstract public function driverRepositorySpy(): DriverRepositorySpy;

    public function driverMoveRepositorySpy(): DriverMoveRepositorySpy
    {
        if (null === $this->driverMoveRepositorySpy) {
            $this->driverMoveRepositorySpy = new DriverMoveRepositorySpy(
                $this->carRepositorySpy(),
                $this->driverRepositorySpy(),
            );
        }

        return $this->driverMoveRepositorySpy;
    }

    public function recordDriverMoveCommandHandler(): RecordDriverMoveCommandHandler
    {
        if (null === $this->recordDriverMoveCommandHandler) {
            $this->recordDriverMoveCommandHandler = new RecordDriverMoveCommandHandler(
                $this->eventDispatcher(),
                $this->uuidGenerator(),
                $this->driverMoveRepositorySpy(),
            );
        }

        return $this->recordDriverMoveCommandHandler;
    }
}
