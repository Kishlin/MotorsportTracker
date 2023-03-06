<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Messaging;

use Exception;
use Kishlin\Backend\MotorsportCache\Calendar\Application\DeprecatedViewCalendar\ViewCalendarQuery;
use Kishlin\Backend\MotorsportTracker\Driver\Application\SearchDriver\SearchDriverQuery;
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

        if ($query instanceof SearchDriverQuery) {
            return $this->testServiceContainer->searchDriverQueryHandler()($query);
        }

        throw new RuntimeException('Unknown query type: ' . get_class($query));
    }
}
