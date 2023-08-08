<?php

declare(strict_types=1);

namespace Kishlin\Apps\MotorsportTracker\Backend\MotorsportCache\Event\Controller;

use Kishlin\Backend\MotorsportCache\Schedule\Domain\Entity\Schedule;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/{championship}/{year}',
    name: 'season_events',
    requirements: [
        'championship' => '[\w\-]+',
        'year'         => '[\d]{4}',
    ],
    methods: [Request::METHOD_GET],
)]
final class SeasonEventsController extends AbstractController
{
    /**
     * @throws InvalidArgumentException
     */
    public function __invoke(CacheItemPoolInterface $cachePool, string $championship, int $year): JsonResponse
    {
        $key = Schedule::computeKey($championship, $year);

        $item = $cachePool->getItem($key);

        if (false === $item->isHit()) {
            return new JsonResponse(null, 404);
        }

        $schedule = $item->get();

        assert($schedule instanceof Schedule);

        return new JsonResponse($schedule->toArray());
    }
}
