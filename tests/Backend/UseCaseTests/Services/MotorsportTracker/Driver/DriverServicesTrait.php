<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Driver;

use Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriver\CreateDriverCommandHandler;
use Kishlin\Backend\MotorsportTracker\Driver\Application\SearchDriver\SearchDriverQueryHandler;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Driver\SaveDriverRepositorySpy;

trait DriverServicesTrait
{
    private ?SaveDriverRepositorySpy $driverRepositorySpy = null;

    private ?CreateDriverCommandHandler $createDriverCommandHandler = null;

    private ?SearchDriverQueryHandler $searchDriverQueryHandler = null;

    public function driverRepositorySpy(): SaveDriverRepositorySpy
    {
        if (null === $this->driverRepositorySpy) {
            $this->driverRepositorySpy = new SaveDriverRepositorySpy($this->countryRepositorySpy());
        }

        return $this->driverRepositorySpy;
    }

    public function createDriverCommandHandler(): CreateDriverCommandHandler
    {
        if (null === $this->createDriverCommandHandler) {
            $this->createDriverCommandHandler = new CreateDriverCommandHandler(
                $this->driverRepositorySpy(),
                $this->uuidGenerator(),
            );
        }

        return $this->createDriverCommandHandler;
    }

    public function searchDriverQueryHandler(): SearchDriverQueryHandler
    {
        if (null === $this->searchDriverQueryHandler) {
            $this->searchDriverQueryHandler = new SearchDriverQueryHandler(
                $this->driverRepositorySpy(),
            );
        }

        return $this->searchDriverQueryHandler;
    }
}
