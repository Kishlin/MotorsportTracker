<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\ViewCalendar;

use DateInterval;
use DateTimeImmutable;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryHandler;

final class ViewCalendarQueryHandler implements QueryHandler
{
    public function __construct(
        private CalendarViewGateway $calendarViewGateway,
    ) {
    }

    public function __invoke(ViewCalendarQuery $query): ViewCalendarResponse
    {
        $date = DateTimeImmutable::createFromFormat('Y-F-d H:i:s', "{$query->year()}-{$query->month()}-01 00:00:00");

        if (false === $date) {
            throw new NotAValidMonthAndYearException();
        }

        $startDate = $date->sub(new DateInterval('P7D'));
        $endDate   = $date->add(new DateInterval('P1M6D'));

        return ViewCalendarResponse::fromView(
            $this->calendarViewGateway->viewAt($startDate, $endDate),
        );
    }
}
