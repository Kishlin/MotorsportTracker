<?php

/** @noinspection PhpUnnecessaryStaticReferenceInspection */

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache;

use DateInterval;
use DateTimeInterface;
use LogicException;
use Psr\Cache\CacheItemInterface;

final class CacheItemStub implements CacheItemInterface
{
    private mixed $value;

    public function __construct(
        private readonly string $key,
        private bool $isHit,
    ) {}

    public function getKey(): string
    {
        return $this->key;
    }

    public function get(): mixed
    {
        if ($this->isHit()) {
            return $this->value;
        }

        return null;
    }

    public function isHit(): bool
    {
        return $this->isHit;
    }

    public function set(mixed $value): static
    {
        $this->value = $value;
        $this->isHit = true;

        return $this;
    }

    public function expiresAt(?DateTimeInterface $expiration): static
    {
        throw new LogicException('Not implemented');
    }

    public function expiresAfter(null|DateInterval|int $time): static
    {
        throw new LogicException('Not implemented');
    }
}
