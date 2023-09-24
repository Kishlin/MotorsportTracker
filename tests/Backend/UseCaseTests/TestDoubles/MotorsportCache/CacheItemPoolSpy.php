<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache;

use LogicException;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;

final class CacheItemPoolSpy implements CacheItemPoolInterface
{
    /** @var array<string, CacheItemInterface> */
    private array $items = [];

    public function getItem(string $key): CacheItemInterface
    {
        if (isset($this->items[$key])) {
            return $this->items[$key];
        }

        return new CacheItemStub($key, false);
    }

    /**
     * @return CacheItemInterface[]
     *
     * @throws InvalidArgumentException
     */
    public function getItems(array $keys = []): iterable
    {
        return array_map(
            fn (string $key) => $this->getItem($key),
            $keys,
        );
    }

    public function hasItem(string $key): bool
    {
        return isset($this->items[$key]);
    }

    public function clear(): bool
    {
        $this->items = [];

        return true;
    }

    public function deleteItem(string $key): bool
    {
        unset($this->items[$key]);

        return true;
    }

    public function deleteItems(array $keys): bool
    {
        foreach ($keys as $key) {
            unset($this->items[$key]);
        }

        return true;
    }

    public function save(CacheItemInterface $item): bool
    {
        $this->items[$item->getKey()] = $item;

        return true;
    }

    public function saveDeferred(CacheItemInterface $item): bool
    {
        throw new LogicException('Not implemented');
    }

    public function commit(): bool
    {
        throw new LogicException('Not implemented');
    }
}
