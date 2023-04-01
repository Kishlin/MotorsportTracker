<?php

declare(strict_types=1);

namespace Kishlin\Apps\MotorsportTracker\Backend\MotorsportCache\Schedule\Controller;

use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewSeasonSchedule\ViewSeasonScheduleQuery;
use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewSeasonSchedule\ViewSeasonScheduleResponse;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/view/{championship}/{year}',
    name: 'events_schedule',
    requirements: [
        'championship' => '[\w\-]+',
        'year'         => '[\d]{4}',
    ],
    methods: [Request::METHOD_GET],
)]
final class ViewSeasonScheduleController extends AbstractController
{
    public function __invoke(QueryBus $queryBus, string $championship, int $year): JsonResponse
    {
        /** @var ViewSeasonScheduleResponse $scheduleResponse */
        $scheduleResponse = $queryBus->ask(ViewSeasonScheduleQuery::fromScalars($championship, $year));

        return new JsonResponse($scheduleResponse->schedule()->toArray());
    }
}
