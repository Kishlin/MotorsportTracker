<?php

declare(strict_types=1);

namespace Kishlin\Apps\MotorsportTracker\Backend\MotorsportCache\EventGraph\Controller;

use Kishlin\Backend\MotorsportCache\EventGraph\Application\ViewGraphDataForEvent\ViewGraphDataForEventQuery;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\ViewGraphDataForEvent\ViewGraphDataForEventResponse;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/{event}',
    name: 'events_graphs',
    requirements: [
        'event' => '^[0-9a-fA-F]{8}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{12}$',
    ],
    methods: [Request::METHOD_GET],
)]
final class EventGraphsController extends AbstractController
{
    public function __invoke(QueryBus $queryBus, string $event): JsonResponse
    {
        /** @var ViewGraphDataForEventResponse $response */
        $response = $queryBus->ask(ViewGraphDataForEventQuery::fromScalars($event));

        return new JsonResponse($response->view()->toArray());
    }
}
