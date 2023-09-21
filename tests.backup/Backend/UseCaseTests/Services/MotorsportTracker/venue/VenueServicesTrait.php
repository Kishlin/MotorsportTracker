<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\venue;

use Kishlin\Backend\MotorsportTracker\Venue\Application\CreateVenueIfNotExists\CreateVenueIfNotExistsCommandHandler;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Venue\SaveVenueRepositorySpy;

trait VenueServicesTrait
{
    private ?SaveVenueRepositorySpy $venueRepositorySpy = null;

    private ?CreateVenueIfNotExistsCommandHandler $createVenueIfNotExistsCommandHandler = null;

    public function venueRepositorySpy(): SaveVenueRepositorySpy
    {
        if (null === $this->venueRepositorySpy) {
            $this->venueRepositorySpy = new SaveVenueRepositorySpy($this->countryRepositorySpy());
        }

        return $this->venueRepositorySpy;
    }

    public function createVenueIfNotExistsCommandHandler(): CreateVenueIfNotExistsCommandHandler
    {
        if (null === $this->createVenueIfNotExistsCommandHandler) {
            $this->createVenueIfNotExistsCommandHandler = new CreateVenueIfNotExistsCommandHandler(
                $this->venueRepositorySpy(),
                $this->venueRepositorySpy(),
                $this->uuidGenerator(),
                $this->eventDispatcher(),
            );
        }

        return $this->createVenueIfNotExistsCommandHandler;
    }
}
