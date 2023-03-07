<?php

declare(strict_types=1);

namespace Kishlin\Apps\MotorsportTracker\Backend\MotorsportCache\Calendar\Controller;

use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewCalendarEvents\ViewCalendarEventsQuery;
use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewCalendarEvents\ViewCalendarEventsResponse;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/view/{start}/{end}',
    name: 'events_calendar',
    requirements: [
        'start' => '[\d]{4}-[\d]{2}-[\d]{2}',
        'end'   => '[\d]{4}-[\d]{2}-[\d]{2}',
    ],
    methods: [Request::METHOD_GET],
)]
final class ViewCalendarEventsController extends AbstractController
{
    public function __invoke(QueryBus $queryBus, string $start, string $end): JsonResponse
    {
        /** @var ViewCalendarEventsResponse $calendarResponse */
        $calendarResponse = $queryBus->ask(ViewCalendarEventsQuery::fromScalars("{$start} 00:00:00", "{$end} 23:59:59"));

        return new JsonResponse($calendarResponse->calendarView()->toArray());
    }
}
