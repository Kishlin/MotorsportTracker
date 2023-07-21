<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Application\ViewSeasonStandings;

use Kishlin\Backend\Shared\Domain\Bus\Query\QueryHandler;

final readonly class ViewSeasonStandingsQueryHandler implements QueryHandler
{
    public function __construct(
        private SeasonStandingsGateway $seasonStandingsGateway,
    ) {
    }

    public function __invoke(ViewSeasonStandingsQuery $query): ViewSeasonStandingsResponse
    {
        return ViewSeasonStandingsResponse::withView(
            $this->seasonStandingsGateway->viewForSeason($query->championshipSlug(), $query->year())
        );
    }
}
