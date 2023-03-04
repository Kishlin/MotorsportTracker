<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Venue\Application\SearchVenue;

use Kishlin\Backend\Shared\Domain\Bus\Query\Response;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class SearchVenueResponse implements Response
{
    private function __construct(
        private readonly UuidValueObject $venueId,
    ) {
    }

    public function venueId(): UuidValueObject
    {
        return $this->venueId;
    }

    public static function fromObject(UuidValueObject $venueId): self
    {
        return new self($venueId);
    }
}
