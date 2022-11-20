<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Venue\Application\SearchVenue;

use Kishlin\Backend\Shared\Domain\Bus\Query\Query;

final class SearchVenueQuery implements Query
{
    private function __construct(
        private string $keyword
    ) {
    }

    public function keyword(): string
    {
        return $this->keyword;
    }

    public static function fromScalar(string $keyword): self
    {
        return new self($keyword);
    }
}
