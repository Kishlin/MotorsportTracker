<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\venue;

use Kishlin\Backend\MotorsportTracker\Venue\Application\CreateVenue\CreateVenueCommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Country\CountryRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Venue\VenueRepositorySpy;

trait VenueServicesTrait
{
    private ?VenueRepositorySpy $venueRepositorySpy = null;

    private ?CreateVenueCommandHandler $createVenueCommandHandler = null;

    abstract public function eventDispatcher(): EventDispatcher;

    abstract public function uuidGenerator(): UuidGenerator;

    abstract public function countryRepositorySpy(): CountryRepositorySpy;

    public function venueRepositorySpy(): VenueRepositorySpy
    {
        if (null === $this->venueRepositorySpy) {
            $this->venueRepositorySpy = new VenueRepositorySpy($this->countryRepositorySpy());
        }

        return $this->venueRepositorySpy;
    }

    public function createVenueCommandHandler(): CreateVenueCommandHandler
    {
        if (null === $this->createVenueCommandHandler) {
            $this->createVenueCommandHandler = new CreateVenueCommandHandler(
                $this->venueRepositorySpy(),
                $this->uuidGenerator(),
                $this->eventDispatcher(),
            );
        }

        return $this->createVenueCommandHandler;
    }
}
