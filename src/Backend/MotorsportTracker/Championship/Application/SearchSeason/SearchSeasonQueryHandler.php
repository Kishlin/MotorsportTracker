<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\SearchSeason;

use Kishlin\Backend\Shared\Domain\Bus\Query\QueryHandler;

final class SearchSeasonQueryHandler implements QueryHandler
{
    public function __construct(
        private SearchSeasonViewer $searchSeasonViewer,
    ) {
    }

    public function __invoke(SearchSeasonQuery $query): SearchSeasonResponse
    {
        $seasonId = $this->searchSeasonViewer->search($query->championship(), $query->year());

        if (null === $seasonId) {
            throw new SeasonNotFoundException();
        }

        return SearchSeasonResponse::fromScalar($seasonId);
    }
}
