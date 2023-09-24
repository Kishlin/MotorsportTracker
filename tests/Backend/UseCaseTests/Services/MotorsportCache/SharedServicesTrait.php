<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportCache;

use Kishlin\Backend\Shared\Domain\Cache\CachePersister;
use Kishlin\Backend\Shared\Infrastructure\Cache\CachePersisterUsingPool;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\CacheItemPoolSpy;

trait SharedServicesTrait
{
    private ?CachePersister $cachePersister = null;

    private ?CacheItemPoolSpy $cacheItemPoolSpy = null;

    public function cachePersister(): CachePersister
    {
        if (null === $this->cachePersister) {
            $this->cachePersister = new CachePersisterUsingPool(
                $this->cacheItemPoolSpy(),
            );
        }

        return $this->cachePersister;
    }

    public function cacheItemPoolSpy(): CacheItemPoolSpy
    {
        if (null === $this->cacheItemPoolSpy) {
            $this->cacheItemPoolSpy = new CacheItemPoolSpy();
        }

        return $this->cacheItemPoolSpy;
    }
}
