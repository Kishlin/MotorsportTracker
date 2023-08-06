<?php

declare(strict_types=1);

namespace Kishlin\Apps\MotorsportTracker\Backend\MotorsportCache\Calendar\Controller;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewCalendarEvents\ViewCalendarEventsQuery;
use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewCalendarEvents\ViewCalendarEventsResponse;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/{month}/{year}',
    name: 'events_calendar_monthly',
    requirements: [
        'month' => 'january|february|march|april|may|june|july|august|september|october|november|december',
        'year'  => '[\d]{4}',
    ],
    methods: [Request::METHOD_GET],
)]
final class MonthlyCalendarEventsController extends AbstractController
{
    private const MONTHS = [
        'january'   => 1,
        'february'  => 2,
        'march'     => 3,
        'april'     => 4,
        'may'       => 5,
        'june'      => 6,
        'july'      => 7,
        'august'    => 8,
        'september' => 9,
        'october'   => 10,
        'november'  => 11,
        'december'  => 12,
    ];

    public function __invoke(QueryBus $queryBus, string $month, int $year): JsonResponse
    {
        /** @var ViewCalendarEventsResponse $calendarResponse */
        $calendarResponse = $queryBus->ask(
            ViewCalendarEventsQuery::fromScalars(
                $this->firstMondayBeforeStartOfMonth($month, $year)->format('Y-m-d 00:00:00'),
                $this->firstSundayAfterEndOfMonth($month, $year)->format('Y-m-d 23:59:59'),
            ),
        );

        return new JsonResponse($calendarResponse->calendarView()->toArray());
    }

    private function firstSundayAfterEndOfMonth(string $month, int $year): DateTimeImmutable
    {
        $monthNumber = self::MONTHS[$month];
        assert(is_int($monthNumber));

        $firstDay = new DateTimeImmutable("{$year}-{$monthNumber}-01");
        $lastDay  = $firstDay->modify('last day of this month');

        $daysUntilSunday = (7 - $lastDay->format('w')) % 7;

        return $lastDay->modify("+{$daysUntilSunday} days");
    }

    private function firstMondayBeforeStartOfMonth(string $month, int $year): DateTimeImmutable
    {
        $monthNumber = self::MONTHS[$month];
        assert(is_int($monthNumber));

        $firstDay = new DateTimeImmutable("{$year}-{$monthNumber}-01");

        $daysUntilMonday = ((int) $firstDay->format('w') + 6) % 7;

        return $firstDay->modify("-{$daysUntilMonday} days");
    }
}
