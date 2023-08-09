<?php

declare(strict_types=1);

namespace Kishlin\Apps\MotorsportTracker\Backend\MotorsportCache\Analytics\Controller;

use Kishlin\Backend\MotorsportCache\Analytics\Domain\Entity\SeasonAnalytics;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/{championship}/{year}/{type}',
    name: 'season_analytics_driver',
    requirements: [
        'championship' => '[\w\-]+',
        'year'         => '[\d]{4}',
        'type'         => 'constructors|drivers|teams',
    ],
    methods: [Request::METHOD_GET],
)]
final class ViewDriverAnalyticsController extends AbstractController
{
    /**
     * @throws InvalidArgumentException
     */
    public function __invoke(CacheItemPoolInterface $cachePool, string $championship, int $year, string $type): JsonResponse
    {
        $key = SeasonAnalytics::computeKey($type, $championship, $year);

        $item = $cachePool->getItem($key);

        if (false === $item->isHit()) {
            return new JsonResponse(null, 404);
        }

        $schedule = $item->get();

        assert($schedule instanceof SeasonAnalytics);

        return new JsonResponse($schedule->toArray());
    }
}
