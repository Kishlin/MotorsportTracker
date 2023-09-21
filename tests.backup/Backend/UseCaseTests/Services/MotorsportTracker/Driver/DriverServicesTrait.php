<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Driver;

use Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriverIfNotExists\CreateDriverIfNotExistsCommandHandler;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Driver\DriverRepositorySpy;

trait DriverServicesTrait
{
    private ?DriverRepositorySpy $driverRepositorySpy = null;

    private ?CreateDriverIfNotExistsCommandHandler $createDriverIfNotExistsCommandHandler = null;

    public function driverRepositorySpy(): DriverRepositorySpy
    {
        if (null === $this->driverRepositorySpy) {
            $this->driverRepositorySpy = new DriverRepositorySpy();
        }

        return $this->driverRepositorySpy;
    }

    public function createDriverIfNotExistsCommandHandler(): CreateDriverIfNotExistsCommandHandler
    {
        if (null === $this->createDriverIfNotExistsCommandHandler) {
            $this->createDriverIfNotExistsCommandHandler = new CreateDriverIfNotExistsCommandHandler(
                $this->driverRepositorySpy(),
                $this->driverRepositorySpy(),
                $this->uuidGenerator(),
                $this->eventDispatcher(),
            );
        }

        return $this->createDriverIfNotExistsCommandHandler;
    }
}
