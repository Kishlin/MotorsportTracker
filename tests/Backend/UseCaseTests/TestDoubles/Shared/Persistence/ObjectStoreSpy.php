<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Persistence;

use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureSaver;
use LogicException;

final class ObjectStoreSpy implements FixtureSaver
{
    /** @var array<string, array<string, array<string, bool|float|int|string>>> */
    private array $objects = [];

    public function save(string $class, string $identifier, array $data): void
    {
        $this->objects[$class][$identifier] = $data;
    }

    /**
     * @return array<string, bool|float|int|string>[]
     */
    public function all(string $location): array
    {
        return $this->objects[$location] ?? [];
    }

    /**
     * @return null|array<string, bool|float|int|string>
     */
    public function get(string $location, string $id): ?array
    {
        return $this->objects[$location][$id] ?? null;
    }

    /**
     * @return array<string, bool|float|int|string>
     */
    public function forceGet(string $className, string $id): array
    {
        $object = $this->get($className, $id);

        if (null === $object) {
            throw new LogicException("Object of class {$className} with id {$id} not found");
        }

        return $object;
    }

    public function resetState(): void
    {
        $this->objects = [];
    }
}
