<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Messaging;

use Exception;
use Kishlin\Backend\MotorsportTracker\Championship\Application\ViewAllChampionships\ViewAllChampionshipsQuery;
use Kishlin\Backend\MotorsportTracker\Event\Application\ViewCalendar\ViewCalendarQuery;
use Kishlin\Backend\MotorsportTracker\Racer\Application\GetAllRacersForDateTime\GetAllRacersForDateTimeQuery;
use Kishlin\Backend\MotorsportTracker\Standing\Application\ViewDriverStandingsForSeason\ViewDriverStandingsForSeasonQuery;
use Kishlin\Backend\MotorsportTracker\Standing\Application\ViewTeamStandingsForSeason\ViewTeamStandingsForSeasonQuery;
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
        if ($query instanceof ViewAllChampionshipsQuery) {
            return $this->testServiceContainer->viewAllChampionshipsQueryHandler()($query);
        }

        if ($query instanceof ViewCalendarQuery) {
            return $this->testServiceContainer->viewCalendarQueryHandler()($query);
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

        throw new RuntimeException('Unknown query type: ' . get_class($query));
    }
}
