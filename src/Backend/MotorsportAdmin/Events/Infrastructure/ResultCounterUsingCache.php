<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportAdmin\Events\Infrastructure;

use Kishlin\Backend\MotorsportAdmin\Events\Application\ListEvents\ResultCounter;
use Kishlin\Backend\MotorsportCache\Result\Domain\Entity\EventResultsByRace;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;

final readonly class ResultCounterUsingCache implements ResultCounter
{
    public function __construct(
        private CacheItemPoolInterface $cacheItemPool,
    ) {}

    /**
     * @throws InvalidArgumentException
     */
    public function resultsForEvent(string $event): int
    {
        $key = EventResultsByRace::computeKey($event);

        $item = $this->cacheItemPool->getItem($key);

        return (int) $item->isHit();
    }
}
