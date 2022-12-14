<?php

declare(strict_types=1);

namespace Kishlin\Apps\MotorsportTracker\Backend\MotorsportTracker\Event\Controller;

use Kishlin\Backend\MotorsportTracker\Event\Application\ViewCalendar\ViewCalendarQuery;
use Kishlin\Backend\MotorsportTracker\Event\Application\ViewCalendar\ViewCalendarResponse;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/calendar/{month}/{year}',
    name: 'events_calendar',
    requirements: [
        'year'  => '\d{4}',
        'month' => '\w+',
    ],
    methods: [Request::METHOD_GET],
)]
final class CalendarController extends AbstractController
{
    public function __invoke(QueryBus $queryBus, string $month, int $year): JsonResponse
    {
        /** @var ViewCalendarResponse $calendarResponse */
        $calendarResponse = $queryBus->ask(ViewCalendarQuery::fromScalars($month, $year));

        return new JsonResponse($calendarResponse->calendarView()->toArray());
    }
}
