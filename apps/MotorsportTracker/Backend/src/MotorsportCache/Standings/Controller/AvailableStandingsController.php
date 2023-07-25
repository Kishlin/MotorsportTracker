<?php

declare(strict_types=1);

namespace Kishlin\Apps\MotorsportTracker\Backend\MotorsportCache\Standings\Controller;

use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/options/{championship}/{year}',
    name: 'season_standings_options',
    requirements: [
        'championship' => '[\w\-]+',
        'year'         => '[\d]{4}',
    ],
    methods: [Request::METHOD_GET],
)]
final class AvailableStandingsController extends AbstractController
{
    use AvailableStandingsFromCacheTrait;

    /**
     * @throws InvalidArgumentException
     */
    public function __invoke(
        CacheItemPoolInterface $cachePool,
        CommandBus $commandBus,
        string $championship,
        int $year,
    ): Response {
        $availableStandings = $this->getAvailableStandingsFromCache($cachePool, $commandBus, $championship, $year);

        return new JsonResponse($availableStandings->toArray());
    }
}
