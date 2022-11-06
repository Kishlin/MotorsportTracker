<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\ViewTeamStandingsForSeason;

use Kishlin\Backend\Shared\Domain\Bus\Query\QueryHandler;

final class ViewTeamStandingsForSeasonQueryHandler implements QueryHandler
{
    public function __construct(
        private TeamStandingsForSeasonGateway $gateway,
    ) {
    }

    public function __invoke(ViewTeamStandingsForSeasonQuery $query): ViewTeamStandingsForSeasonResponse
    {
        return ViewTeamStandingsForSeasonResponse::fromView(
            $this->gateway->view($query->seasonId()),
        );
    }
}
