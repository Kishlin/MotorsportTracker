<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Venue\Application\CreateVenue;

use Kishlin\Backend\MotorsportTracker\Venue\Domain\ValueObject\VenueCountryId;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\ValueObject\VenueName;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final class CreateVenueCommand implements Command
{
    private function __construct(
        private string $name,
        private string $countryId,
    ) {
    }

    public function name(): VenueName
    {
        return new VenueName($this->name);
    }

    public function countryId(): VenueCountryId
    {
        return new VenueCountryId($this->countryId);
    }

    public static function fromScalars(string $name, string $countryId): self
    {
        return new self($name, $countryId);
    }
}
