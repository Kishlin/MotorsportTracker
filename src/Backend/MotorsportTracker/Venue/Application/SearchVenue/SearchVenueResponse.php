<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Venue\Application\SearchVenue;

use Kishlin\Backend\MotorsportTracker\Venue\Domain\ValueObject\VenueId;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final class SearchVenueResponse implements Response
{
    private function __construct(
        private VenueId $venueId,
    ) {
    }

    public function venueId(): VenueId
    {
        return $this->venueId;
    }

    public static function fromScalar(VenueId $venueId): self
    {
        return new self($venueId);
    }
}
