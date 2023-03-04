<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Messaging;

use Exception;
use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewCalendar\ViewCalendarQuery;
use Kishlin\Backend\MotorsportTracker\Championship\Application\SearchSeason\SearchSeasonQuery;
use Kishlin\Backend\MotorsportTracker\Championship\Application\ViewAllChampionships\ViewAllChampionshipsQuery;
use Kishlin\Backend\MotorsportTracker\Driver\Application\SearchDriver\SearchDriverQuery;
use Kishlin\Backend\MotorsportTracker\Team\Application\SearchTeam\SearchTeamQuery;
use Kishlin\Backend\MotorsportTracker\Venue\Application\SearchVenue\SearchVenueQuery;
use Kishlin\Backend\Shared\Domain\Bus\Query\Query;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;
use Kishlin\Tests\Backend\UseCaseTests\TestServiceContainer;
use RuntimeException;

final class TestQueryBus implements QueryBus
{
    public function __construct(
        private readonly TestServiceContainer $testServiceContainer,
    ) {
    }

    /**
     * @throws Exception|RuntimeException
     */
    public function ask(Query $query): Response
    {
        if ($query instanceof ViewCalendarQuery) {
            return $this->testServiceContainer->viewCalendarQueryHandler()($query);
        }

        if ($query instanceof ViewAllChampionshipsQuery) {
            return $this->testServiceContainer->viewAllChampionshipsQueryHandler()($query);
        }

        if ($query instanceof SearchSeasonQuery) {
            return $this->testServiceContainer->searchSeasonQueryHandler()($query);
        }

        if ($query instanceof SearchDriverQuery) {
            return $this->testServiceContainer->searchDriverQueryHandler()($query);
        }

        if ($query instanceof SearchTeamQuery) {
            return $this->testServiceContainer->searchTeamQueryHandler()($query);
        }

        if ($query instanceof SearchVenueQuery) {
            return $this->testServiceContainer->searchVenueQueryHandler()($query);
        }

        throw new RuntimeException('Unknown query type: ' . get_class($query));
    }
}
