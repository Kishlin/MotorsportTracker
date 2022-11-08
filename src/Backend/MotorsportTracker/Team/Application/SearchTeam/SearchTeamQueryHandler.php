<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Application\SearchTeam;

use Kishlin\Backend\Shared\Domain\Bus\Query\QueryHandler;

final class SearchTeamQueryHandler implements QueryHandler
{
    public function __construct(
        private SearchTeamViewer $searchTeamViewer,
    ) {
    }

    public function __invoke(SearchTeamQuery $query): SearchTeamResponse
    {
        $teamId = $this->searchTeamViewer->search($query->keyword());

        if (null === $teamId) {
            throw new TeamNotFoundException();
        }

        return SearchTeamResponse::fromScalar($teamId);
    }
}
