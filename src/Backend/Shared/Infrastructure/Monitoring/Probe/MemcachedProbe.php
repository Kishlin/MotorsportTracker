<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Monitoring\Probe;

use Memcached;
use Psr\Cache\CacheItemPoolInterface;
use ReflectionMethod;
use Symfony\Component\Cache\Adapter\MemcachedAdapter;
use Throwable;

final readonly class MemcachedProbe implements Probe
{
    public function __construct(
        private CacheItemPoolInterface $cache,
        private string $server,
    ) {
    }

    public function name(): string
    {
        return 'Memcached';
    }

    public function isAlive(): bool
    {
        try {
            assert($this->cache instanceof MemcachedAdapter);

            $reflection = new ReflectionMethod($this->cache::class, 'getClient');

            $client = $reflection->invoke($this->cache);

            assert($client instanceof Memcached);
            assert(array_key_exists($this->server, $client->getStats()));
            assert($client->getStats()[$this->server]['pid'] > 0);
        } catch (Throwable) {
            return false;
        }

        return true;
    }
}
