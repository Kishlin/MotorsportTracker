<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Application\ViewCalendarEvents;

use Exception;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryHandler;

final class ViewCalendarEventsQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly ViewCalendarEventsGateway $gateway,
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(ViewCalendarEventsQuery $query): ViewCalendarEventsResponse
    {
        return ViewCalendarEventsResponse::fromView(
            $this->gateway->view($query->start(), $query->end()),
        );
    }
}
