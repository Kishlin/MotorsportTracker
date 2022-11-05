<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Venue;

use Kishlin\Backend\MotorsportTracker\Venue\Application\CreateVenue\VenueCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\Entity\Venue;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\Gateway\VenueGateway;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\ValueObject\VenueId;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Country\CountryRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property Venue[] $objects
 *
 * @method Venue[]    all()
 * @method null|Venue get(VenueId $id)
 */
final class VenueRepositorySpy extends AbstractRepositorySpy implements VenueGateway
{
    public function __construct(
        private CountryRepositorySpy $countryRepositorySpy,
    ) {
    }

    public function save(Venue $venue): void
    {
        if (false === $this->countryRepositorySpy->has($venue->countryId())
            || $this->venueNameIsAlreadyTaken($venue)) {
            throw new VenueCreationFailureException();
        }

        $this->objects[$venue->id()->value()] = $venue;
    }

    private function venueNameIsAlreadyTaken(Venue $venue): bool
    {
        foreach ($this->objects as $savedVenue) {
            if ($savedVenue->name()->equals($venue->name())) {
                return true;
            }
        }

        return false;
    }
}
