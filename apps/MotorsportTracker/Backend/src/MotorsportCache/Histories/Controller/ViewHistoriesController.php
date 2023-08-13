<?php

declare(strict_types=1);

namespace Kishlin\Apps\MotorsportTracker\Backend\MotorsportCache\Histories\Controller;

use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Entity\Histories;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/{event}',
    name: 'events_histories',
    requirements: [
        'event' => '^[0-9a-fA-F]{8}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{12}$',
    ],
    methods: [Request::METHOD_GET],
)]
final class ViewHistoriesController extends AbstractController
{
    /**
     * @throws InvalidArgumentException
     */
    public function __invoke(CacheItemPoolInterface $cachePool, string $event): JsonResponse
    {
        $key = Histories::computeKey($event);

        $item = $cachePool->getItem($key);

        if (false === $item->isHit()) {
            return new JsonResponse(null, 404);
        }

        $schedule = $item->get();

        assert($schedule instanceof Histories);

        return new JsonResponse($schedule->toArray());
    }
}
