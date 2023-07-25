<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\Cache;

interface CachePersister
{
    /**
     * @param array<string, int|string> $keyData
     */
    public function save(CacheItem $cacheItem, array $keyData): void;
}
