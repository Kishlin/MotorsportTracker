<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Venue\Application\SearchVenue;

use Kishlin\Backend\Shared\Domain\Bus\Query\QueryHandler;

final class SearchVenueQueryHandler implements QueryHandler
{
    public function __construct(
        private SearchVenueViewer $viewer,
    ) {
    }

    public function __invoke(SearchVenueQuery $query): SearchVenueResponse
    {
        $venueId = $this->viewer->search($query->keyword());

        if (null === $venueId) {
            throw new VenueNotFoundException();
        }

        return SearchVenueResponse::fromScalar($venueId);
    }
}