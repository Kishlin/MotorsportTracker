<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Utils;

use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use RuntimeException;

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

    public function safeGet(UuidValueObject $id): AggregateRoot
    {
        $idString = $id->value();

        if (false === array_key_exists($idString, $this->objects)) {
            throw new RuntimeException("Requesting object with id {$idString} which does not exist.");
        }

        return $this->objects[$id->value()];
    }

    /**
     * @return AggregateRoot[]
     */
    public function all(): array
    {
        return $this->objects;
    }

    public function add(AggregateRoot $aggregateRoot): void
    {
        if (method_exists($aggregateRoot, 'id')) {
            /** @var UuidValueObject $id */
            $id = $aggregateRoot->id();

            $this->objects[$id->value()] = $aggregateRoot;
        } else {
            throw new RuntimeException('Unsure how to store aggregate root of type ' . $aggregateRoot::class);
        }
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
