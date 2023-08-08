<?php

declare(strict_types=1);

namespace Kishlin\Apps\MotorsportTracker\Backend\MotorsportCache\Results\Controller;

use Kishlin\Backend\MotorsportCache\Result\Domain\Entity\EventResultsByRace;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
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
    /**
     * @throws InvalidArgumentException
     */
    public function __invoke(CacheItemPoolInterface $cachePool, string $event): JsonResponse
    {
        $key = EventResultsByRace::computeKey($event);

        $item = $cachePool->getItem($key);

        if (false === $item->isHit()) {
            return new JsonResponse(null, 404);
        }

        $schedule = $item->get();

        assert($schedule instanceof EventResultsByRace);

        return new JsonResponse($schedule->toArray());
    }
}
