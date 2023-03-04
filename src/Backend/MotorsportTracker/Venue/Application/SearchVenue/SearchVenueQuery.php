<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Venue\Application\SearchVenue;

use Kishlin\Backend\Shared\Domain\Bus\Query\Query;

final class SearchVenueQuery implements Query
{
    private function __construct(
        private readonly string $slug,
    ) {
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public static function fromScalar(string $slug): self
    {
        return new self($slug);
    }
}
