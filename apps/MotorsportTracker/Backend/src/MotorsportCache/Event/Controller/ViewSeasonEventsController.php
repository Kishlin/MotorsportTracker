<?php

declare(strict_types=1);

namespace Kishlin\Apps\MotorsportTracker\Backend\MotorsportCache\Event\Controller;

use Kishlin\Backend\MotorsportCache\Event\Application\ViewSeasonEvents\SeasonEventsNotFoundException;
use Kishlin\Backend\MotorsportCache\Event\Application\ViewSeasonEvents\ViewSeasonEventsQuery;
use Kishlin\Backend\MotorsportCache\Event\Application\ViewSeasonEvents\ViewSeasonEventsResponse;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/{championshipSlug}/{year}',
    name: 'events_season',
    requirements: [
        'championshipSlug' => '[\w\-]+',
        'year'             => '[\d]{4}',
    ],
    methods: [Request::METHOD_GET],
)]
final class ViewSeasonEventsController extends AbstractController
{
    public function __invoke(QueryBus $queryBus, string $championshipSlug, int $year): JsonResponse
    {
        try {
            /** @var ViewSeasonEventsResponse $seasonEventsResponse */
            $seasonEventsResponse = $queryBus->ask(ViewSeasonEventsQuery::fromScalars($championshipSlug, $year));
        } catch (SeasonEventsNotFoundException) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($seasonEventsResponse->view()->toArray());
    }
}
