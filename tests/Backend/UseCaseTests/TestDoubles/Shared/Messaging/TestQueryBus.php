<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Messaging;

use Exception;
use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewCalendarEvents\ViewCalendarEventsQuery;
use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewSeasonEvents\ViewSeasonEventsQuery;
use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewSeasonSchedule\ViewSeasonScheduleQuery;
use Kishlin\Backend\MotorsportCache\Result\Application\ViewEventResultsByRace\ViewEventResultsByRaceQuery;
use Kishlin\Backend\MotorsportTracker\Result\Application\FindEntryForSessionAndNumber\FindEntryForSessionAndNumberQuery;
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
        if ($query instanceof ViewCalendarEventsQuery) {
            return $this->testServiceContainer->viewCalendarEventsQueryHandler()($query);
        }

        if ($query instanceof ViewSeasonScheduleQuery) {
            return $this->testServiceContainer->viewSeasonScheduleQueryHandler()($query);
        }

        if ($query instanceof ViewSeasonEventsQuery) {
            return $this->testServiceContainer->viewSeasonEventsQueryHandler()($query);
        }

        if ($query instanceof ViewEventResultsByRaceQuery) {
            return $this->testServiceContainer->viewEventResultsByRaceQueryHandler()($query);
        }

        if ($query instanceof FindEntryForSessionAndNumberQuery) {
            return $this->testServiceContainer->findEntryForSessionAndNumberQueryHandler()($query);
        }

        throw new RuntimeException('Unknown query type: ' . get_class($query));
    }
}
