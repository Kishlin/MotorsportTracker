<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\venue;

use Kishlin\Backend\MotorsportTracker\Venue\Application\CreateVenue\CreateVenueCommandHandler;
use Kishlin\Backend\MotorsportTracker\Venue\Application\CreateVenueIfNotExists\CreateVenueIfNotExistsCommandHandler;
use Kishlin\Backend\MotorsportTracker\Venue\Application\SearchVenue\SearchVenueQueryHandler;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Country\SaveSearchCountryRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Venue\SaveVenueRepositorySpy;

trait VenueServicesTrait
{
    private ?SaveVenueRepositorySpy $venueRepositorySpy = null;

    private ?SearchVenueQueryHandler $searchVenueQueryHandler = null;

    private ?CreateVenueCommandHandler $createVenueCommandHandler = null;

    private ?CreateVenueIfNotExistsCommandHandler $createVenueIfNotExistsCommandHandler = null;

    abstract public function uuidGenerator(): UuidGenerator;

    abstract public function countryRepositorySpy(): SaveSearchCountryRepositorySpy;

    public function venueRepositorySpy(): SaveVenueRepositorySpy
    {
        if (null === $this->venueRepositorySpy) {
            $this->venueRepositorySpy = new SaveVenueRepositorySpy($this->countryRepositorySpy());
        }

        return $this->venueRepositorySpy;
    }

    public function searchVenueQueryHandler(): SearchVenueQueryHandler
    {
        if (null === $this->searchVenueQueryHandler) {
            $this->searchVenueQueryHandler = new SearchVenueQueryHandler(
                $this->venueRepositorySpy(),
            );
        }

        return $this->searchVenueQueryHandler;
    }

    public function createVenueCommandHandler(): CreateVenueCommandHandler
    {
        if (null === $this->createVenueCommandHandler) {
            $this->createVenueCommandHandler = new CreateVenueCommandHandler(
                $this->venueRepositorySpy(),
                $this->uuidGenerator(),
            );
        }

        return $this->createVenueCommandHandler;
    }

    public function createVenueIfNotExistsCommandHandler(): CreateVenueIfNotExistsCommandHandler
    {
        if (null === $this->createVenueIfNotExistsCommandHandler) {
            $this->createVenueIfNotExistsCommandHandler = new CreateVenueIfNotExistsCommandHandler(
                $this->venueRepositorySpy(),
                $this->venueRepositorySpy(),
                $this->uuidGenerator(),
            );
        }

        return $this->createVenueIfNotExistsCommandHandler;
    }
}
