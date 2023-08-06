<?php

declare(strict_types=1);

namespace Kishlin\Apps\MotorsportTracker\Backend\MotorsportCache\Calendar\Controller;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewCalendarEvents\ViewCalendarEventsQuery;
use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewCalendarEvents\ViewCalendarEventsResponse;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Kishlin\Backend\Shared\Domain\Time\Clock;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/upcoming',
    name: 'events_calendar_upcoming',
    methods: [Request::METHOD_GET],
)]
final class UpcomingCalendarEventsController extends AbstractController
{
    public function __invoke(QueryBus $queryBus, Clock $clock): JsonResponse
    {
        $now = $clock->now();

        $previousMonday    = $this->previousMonday($now);
        $sunday14daysLater = $previousMonday->modify('+20 days');

        /** @var ViewCalendarEventsResponse $calendarResponse */
        $calendarResponse = $queryBus->ask(
            ViewCalendarEventsQuery::fromScalars(
                $previousMonday->format('Y-m-d 00:00:00'),
                $sunday14daysLater->format('Y-m-d 23:59:59'),
            ),
        );

        return new JsonResponse($calendarResponse->calendarView()->toArray());
    }

    private function previousMonday(DateTimeImmutable $now): DateTimeImmutable
    {
        if (1 === (int) $now->format('N')) {
            return clone $now;
        }

        $daysUntilMonday = ((int) $now->format('N') + 6) % 7;

        return $now->modify("-{$daysUntilMonday} days");
    }
}
