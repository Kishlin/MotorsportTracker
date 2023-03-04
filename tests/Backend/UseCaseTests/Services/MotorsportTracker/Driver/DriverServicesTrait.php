<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Driver;

use Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriver\CreateDriverCommandHandler;
use Kishlin\Backend\MotorsportTracker\Driver\Application\SearchDriver\SearchDriverQueryHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Country\SaveSearchCountryRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Driver\DriverRepositorySpy;

trait DriverServicesTrait
{
    private ?DriverRepositorySpy $driverRepositorySpy = null;

    private ?CreateDriverCommandHandler $createDriverCommandHandler = null;

    private ?SearchDriverQueryHandler $searchDriverQueryHandler = null;

    abstract public function eventDispatcher(): EventDispatcher;

    abstract public function uuidGenerator(): UuidGenerator;

    abstract public function countryRepositorySpy(): SaveSearchCountryRepositorySpy;

    public function driverRepositorySpy(): DriverRepositorySpy
    {
        if (null === $this->driverRepositorySpy) {
            $this->driverRepositorySpy = new DriverRepositorySpy($this->countryRepositorySpy());
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
