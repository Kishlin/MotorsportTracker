<?php

declare(strict_types=1);

namespace Kishlin\Apps\MotorsportTracker\Backend\MotorsportCache\Event\Controller;

use Kishlin\Backend\MotorsportCache\Event\Application\ViewCachedEvents\ViewCachedEventsQuery;
use Kishlin\Backend\MotorsportCache\Event\Application\ViewCachedEvents\ViewCachedEventsResponse;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/',
    name: 'events_cached',
    methods: [Request::METHOD_GET],
)]
final class ViewCachedEventsController extends AbstractController
{
    public function __invoke(QueryBus $queryBus): JsonResponse
    {
        /** @var ViewCachedEventsResponse $response */
        $response = $queryBus->ask(ViewCachedEventsQuery::create());

        return new JsonResponse($response->events()->toArray());
    }
}
