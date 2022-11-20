<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Messaging;

use Exception;
use Kishlin\Backend\MotorsportTracker\Car\Application\SearchCar\SearchCarQuery;
use Kishlin\Backend\MotorsportTracker\Championship\Application\SearchSeason\SearchSeasonQuery;
use Kishlin\Backend\MotorsportTracker\Championship\Application\ViewAllChampionships\ViewAllChampionshipsQuery;
use Kishlin\Backend\MotorsportTracker\Driver\Application\SearchDriver\SearchDriverQuery;
use Kishlin\Backend\MotorsportTracker\Event\Application\SearchEvent\SearchEventQuery;
use Kishlin\Backend\MotorsportTracker\Event\Application\SearchEventStepIdAndDateTime\SearchEventStepIdAndDateTimeQuery;
use Kishlin\Backend\MotorsportTracker\Event\Application\ViewCalendar\ViewCalendarQuery;
use Kishlin\Backend\MotorsportTracker\Racer\Application\GetAllRacersForDateTime\GetAllRacersForDateTimeQuery;
use Kishlin\Backend\MotorsportTracker\Standing\Application\ViewDriverStandingsForSeason\ViewDriverStandingsForSeasonQuery;
use Kishlin\Backend\MotorsportTracker\Standing\Application\ViewTeamStandingsForSeason\ViewTeamStandingsForSeasonQuery;
use Kishlin\Backend\MotorsportTracker\Team\Application\SearchTeam\SearchTeamQuery;
use Kishlin\Backend\Shared\Domain\Bus\Query\Query;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;
use Kishlin\Tests\Backend\UseCaseTests\TestServiceContainer;
use RuntimeException;

final class TestQueryBus implements QueryBus
{
    public function __construct(
        private TestServiceContainer $testServiceContainer,
    ) {
    }

    /**
     * @throws Exception|RuntimeException
     */
    public function ask(Query $query): Response
    {
        if ($query instanceof SearchCarQuery) {
            return $this->testServiceContainer->searchCarQueryHandler()($query);
        }

        if ($query instanceof ViewAllChampionshipsQuery) {
            return $this->testServiceContainer->viewAllChampionshipsQueryHandler()($query);
        }

        if ($query instanceof SearchSeasonQuery) {
            return $this->testServiceContainer->searchSeasonQueryHandler()($query);
        }

        if ($query instanceof SearchEventQuery) {
            return $this->testServiceContainer->searchEventQueryHandler()($query);
        }

        if ($query instanceof SearchEventStepIdAndDateTimeQuery) {
            return $this->testServiceContainer->searchEventStepIdAndDateTimeQueryHandler()($query);
        }

        if ($query instanceof ViewCalendarQuery) {
            return $this->testServiceContainer->viewCalendarQueryHandler()($query);
        }

        if ($query instanceof SearchDriverQuery) {
            return $this->testServiceContainer->searchDriverQueryHandler()($query);
        }

        if ($query instanceof GetAllRacersForDateTimeQuery) {
            return $this->testServiceContainer->getAllRacersForDateTimeQueryHandler()($query);
        }

        if ($query instanceof ViewDriverStandingsForSeasonQuery) {
            return $this->testServiceContainer->viewDriverStandingsForSeasonQueryHandler()($query);
        }

        if ($query instanceof ViewTeamStandingsForSeasonQuery) {
            return $this->testServiceContainer->viewTeamStandingsForSeasonQueryHandler()($query);
        }

        if ($query instanceof SearchTeamQuery) {
            return $this->testServiceContainer->searchTeamQueryHandler()($query);
        }

        throw new RuntimeException('Unknown query type: ' . get_class($query));
    }
}
