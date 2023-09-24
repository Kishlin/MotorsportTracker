<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Cache;

use Kishlin\Backend\Shared\Domain\Cache\CacheItem;
use Kishlin\Backend\Shared\Domain\Cache\CachePersister;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use RuntimeException;

final readonly class CachePersisterUsingPool implements CachePersister
{
    public function __construct(
        private CacheItemPoolInterface $cacheItemPool,
        private ?LoggerInterface $logger = null,
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function save(CacheItem $cacheItem, array $keyData): void
    {
        if (false === method_exists($cacheItem, 'computeKey')) {
            throw new RuntimeException('No way to compute key for item of class ' . $cacheItem::class);
        }

        $key = call_user_func_array([$cacheItem, 'computeKey'], $keyData);
        assert(is_string($key));

        $item = $this->cacheItemPool->getItem($key);
        $item->set($cacheItem);

        $this->cacheItemPool->save($item);

        $this->logger?->info("Saved new cache item with key {$key}");
    }
}
