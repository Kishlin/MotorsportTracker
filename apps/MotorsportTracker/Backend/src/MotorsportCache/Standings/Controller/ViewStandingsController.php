<?php

declare(strict_types=1);

namespace Kishlin\Apps\MotorsportTracker\Backend\MotorsportCache\Standings\Controller;

use Kishlin\Backend\MotorsportCache\Standing\Domain\Entity\ConstructorStandings;
use Kishlin\Backend\MotorsportCache\Standing\Domain\Entity\DriverStandings;
use Kishlin\Backend\MotorsportCache\Standing\Domain\Entity\TeamStandings;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/details/{championship}/{year}/{type}',
    name: 'season_standings_details',
    requirements: [
        'championship' => '[\w\-]+',
        'year'         => '[\d]{4}',
        'type'         => '[\w]+',
    ],
    methods: [Request::METHOD_GET],
)]
final class ViewStandingsController extends AbstractController
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
        string $type,
    ): Response {
        $availableStandings = $this->getAvailableStandingsFromCache($cachePool, $commandBus, $championship, $year);

        if (false === $availableStandings->has($type)) {
            throw new NotFoundHttpException();
        }

        $standingsClass = match ($type) {
            'constructor' => ConstructorStandings::class,
            'team'        => TeamStandings::class,
            'driver'      => DriverStandings::class,
            default       => throw new NotFoundHttpException(),
        };

        $key = $this->computeKey($standingsClass, $championship, $year);

        $item = $cachePool->getItem($key);

        if (false === $item->isHit()) {
            throw new RuntimeException('Standings should not be a miss if scrapping standing succeeded.');
        }

        $standings = $item->get();

        assert($standings instanceof $standingsClass);

        return new JsonResponse($standings->toArray());
    }

    /**
     * @param class-string $standingsClass
     */
    private function computeKey(string $standingsClass, string $championship, int $year): string
    {
        assert(method_exists($standingsClass, 'computeKey'));

        $key = call_user_func_array([$standingsClass, 'computeKey'], ['championship' => $championship, 'year' => $year]);
        assert(is_string($key));

        return $key;
    }
}