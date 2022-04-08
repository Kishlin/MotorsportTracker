<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Venue\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Venue\Domain\DomainEvent\VenueCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\ValueObject\VenueCountryId;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\ValueObject\VenueId;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\ValueObject\VenueName;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;

final class Venue extends AggregateRoot
{
    private function __construct(
        private VenueId $id,
        private VenueName $name,
        private VenueCountryId $countryId,
    ) {
    }

    public static function create(VenueId $id, VenueName $name, VenueCountryId $countryId): self
    {
        $venue = new self($id, $name, $countryId);

        $venue->record(new VenueCreatedDomainEvent($id));

        return $venue;
    }

    /**
     * @internal Only use to get a test object.
     */
    public static function instance(VenueId $id, VenueName $name, VenueCountryId $countryId): self
    {
        return new self($id, $name, $countryId);
    }

    public function id(): VenueId
    {
        return $this->id;
    }

    public function name(): VenueName
    {
        return $this->name;
    }

    public function countryId(): VenueCountryId
    {
        return $this->countryId;
    }
}
