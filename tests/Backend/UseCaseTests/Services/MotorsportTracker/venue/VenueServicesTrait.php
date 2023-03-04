<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\venue;

use Kishlin\Backend\MotorsportTracker\Venue\Application\CreateVenue\CreateVenueCommandHandler;
use Kishlin\Backend\MotorsportTracker\Venue\Application\SearchVenue\SearchVenueQueryHandler;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Country\SaveSearchCountryRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Venue\VenueRepositorySpy;

trait VenueServicesTrait
{
    private ?VenueRepositorySpy $venueRepositorySpy = null;

    private ?SearchVenueQueryHandler $searchVenueQueryHandler = null;

    private ?CreateVenueCommandHandler $createVenueCommandHandler = null;

    abstract public function uuidGenerator(): UuidGenerator;

    abstract public function countryRepositorySpy(): SaveSearchCountryRepositorySpy;

    public function venueRepositorySpy(): VenueRepositorySpy
    {
        if (null === $this->venueRepositorySpy) {
            $this->venueRepositorySpy = new VenueRepositorySpy($this->countryRepositorySpy());
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
}
