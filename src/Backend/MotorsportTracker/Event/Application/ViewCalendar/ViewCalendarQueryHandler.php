<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\ViewCalendar;

use Kishlin\Backend\Shared\Domain\Bus\Query\QueryHandler;

final class ViewCalendarQueryHandler implements QueryHandler
{
    public function __construct(
        private CalendarViewGateway $calendarViewGateway,
    ) {
    }

    public function __invoke(ViewCalendarQuery $query): ViewCalendarResponse
    {
        return ViewCalendarResponse::fromView(
            $this->calendarViewGateway->view(),
        );
    }
}
