<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\ViewDriverStandingsForSeason;

use Kishlin\Backend\Shared\Domain\Bus\Query\QueryHandler;

final class ViewDriverStandingsForSeasonQueryHandler implements QueryHandler
{
    public function __construct(
        private DriverStandingsForSeasonGateway $gateway,
    ) {
    }

    public function __invoke(ViewDriverStandingsForSeasonQuery $query): ViewDriverStandingsForSeasonResponse
    {
        return ViewDriverStandingsForSeasonResponse::fromView(
            $this->gateway->view($query->seasonId()),
        );
    }
}
