<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Persistence\Repository;

use Kishlin\Backend\Persistence\Core\Connection\Connection;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\Tools;
use ReflectionException;

abstract class Repository
{
    public function __construct(
        protected Connection $connection
    ) {}

    protected function persist(AggregateRoot $entity): void
    {
        $this->connection->insert($this->computeLocation($entity), $entity->mappedData());
    }

    protected function computeLocation(AggregateRoot $entity): string
    {
        try {
            return Tools::fromPascalToSnakeCase(Tools::shortClassName($entity));
        } catch (ReflectionException) {
            return $entity::class;
        }
    }
}
