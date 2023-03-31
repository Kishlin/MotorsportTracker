<?php

declare(strict_types=1);

namespace Kishlin\Apps\MotorsportTracker\Backend\MotorsportCache\Results\Controller;

use Kishlin\Backend\MotorsportCache\Result\Application\ViewEventResultsByRace\ViewEventResultsByRaceQuery;
use Kishlin\Backend\MotorsportCache\Result\Application\ViewEventResultsByRace\ViewEventResultsByRaceResponse;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/{event}',
    name: 'events_results',
    requirements: [
        'event' => '^[0-9a-fA-F]{8}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{12}$',
    ],
    methods: [Request::METHOD_GET],
)]
final class ViewResultsController extends AbstractController
{
    public function __invoke(QueryBus $queryBus, string $event): JsonResponse
    {
        /** @var ViewEventResultsByRaceResponse $response */
        $response = $queryBus->ask(ViewEventResultsByRaceQuery::fromScalars($event));

        return new JsonResponse($response->view()->toArray());
    }
}
