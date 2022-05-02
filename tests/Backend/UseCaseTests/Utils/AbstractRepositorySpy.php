<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Utils;

use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

abstract class AbstractRepositorySpy
{
    /** @var AggregateRoot[] */
    protected array $objects = [];

    public function has(UuidValueObject $id): bool
    {
        return array_key_exists($id->value(), $this->objects);
    }

    public function get(UuidValueObject $id): ?AggregateRoot
    {
        return $this->objects[$id->value()] ?? null;
    }

    public function count(): int
    {
        return count($this->objects);
    }

    public function clear(): void
    {
        $this->objects = [];
    }
}
