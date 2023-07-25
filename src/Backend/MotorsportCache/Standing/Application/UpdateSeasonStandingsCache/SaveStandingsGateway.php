<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Application\UpdateSeasonStandingsCache;

use Kishlin\Backend\Shared\Domain\Cache\CacheItem;

interface SaveStandingsGateway
{
    /**
     * @param array<int, int|string> $keyData
     */
    public function save(CacheItem $cacheItem, array $keyData): void;
}
