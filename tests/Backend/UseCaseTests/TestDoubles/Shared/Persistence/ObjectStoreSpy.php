<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Persistence;

use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\Tools;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureSaver;
use LogicException;
use ReflectionException;

final class ObjectStoreSpy extends FixtureSaver
{
    /** @var array<string, array<string, AggregateRoot>> */
    private array $objects = [];

    /**
     * @param class-string<object> $className
     *
     * @return AggregateRoot[]
     */
    public function all(string $className): array
    {
        return $this->objects[$this->location($className)] ?? [];
    }

    /**
     * @param class-string<object> $className
     */
    public function get(string $className, string $id): ?AggregateRoot
    {
        $location = $this->location($className);

        return $this->objects[$location][$id] ?? null;
    }

    /**
     * @param class-string<object> $className
     */
    public function forceGet(string $className, string $id): AggregateRoot
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

    protected function saveAggregateRoot(AggregateRoot $aggregateRoot): void
    {
        $location = $this->location($aggregateRoot);

        $this->objects[$location][$aggregateRoot->id()->value()] = $aggregateRoot;
    }

    /**
     * @param AggregateRoot|class-string<object> $aggregateClass
     */
    private function location(AggregateRoot|string $aggregateClass): string
    {
        try {
            return Tools::fromPascalToSnakeCase(Tools::shortClassName($aggregateClass));
        } catch (ReflectionException) {
            return $aggregateClass::class;
        }
    }
}
