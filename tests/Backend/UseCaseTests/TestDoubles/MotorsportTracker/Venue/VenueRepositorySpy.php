<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Venue;

use Kishlin\Backend\MotorsportTracker\Venue\Application\CreateVenue\VenueCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Venue\Application\CreateVenue\VenueGateway;
use Kishlin\Backend\MotorsportTracker\Venue\Application\SearchVenue\SearchVenueViewer;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\Entity\Venue;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Country\SaveSearchCountryRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property Venue[] $objects
 *
 * @method Venue[]    all()
 * @method null|Venue get(UuidValueObject $id)
 * @method Venue      safeGet(UuidValueObject $id)
 */
final class VenueRepositorySpy extends AbstractRepositorySpy implements VenueGateway, SearchVenueViewer
{
    public function __construct(
        private readonly SaveSearchCountryRepositorySpy $countryRepositorySpy,
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

    public function search(string $slug): ?UuidValueObject
    {
        foreach ($this->objects as $venue) {
            if ($slug === $venue->slug()->value()) {
                return $venue->id();
            }
        }

        return null;
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
