<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Racer\Application\GetAllRacersForDateTime;

use Exception;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryHandler;

final class GetAllRacersForDateTimeQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly RacersForDateTimeAndSeasonGateway $racersForDateTimeGateway,
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(GetAllRacersForDateTimeQuery $query): GetAllRacersForDateTimeResponse
    {
        return GetAllRacersForDateTimeResponse::fromRacers(
            $this->racersForDateTimeGateway->findRacersForDateTimeAndSeason(
                $query->datetime(),
                $query->seasonId(),
            ),
        );
    }
}
