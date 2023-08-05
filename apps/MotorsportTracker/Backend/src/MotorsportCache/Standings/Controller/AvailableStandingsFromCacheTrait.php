<?php

declare(strict_types=1);

namespace Kishlin\Apps\MotorsportTracker\Backend\MotorsportCache\Standings\Controller;

use Kishlin\Backend\MotorsportCache\Standing\Domain\Entity\AvailableStandings;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;

trait AvailableStandingsFromCacheTrait
{
    /**
     * @throws InvalidArgumentException
     */
    private function getAvailableStandingsFromCache(
        CacheItemPoolInterface $cachePool,
        CommandBus $commandBus,
        string $championship,
        int $year,
    ): ?AvailableStandings {
        $item = $cachePool->getItem(AvailableStandings::computeKey($championship, $year));

        if (false === $item->isHit()) {
            return null;
        }

        $availableStandings = $item->get();
        assert($availableStandings instanceof AvailableStandings);

        return $availableStandings;
    }
}
