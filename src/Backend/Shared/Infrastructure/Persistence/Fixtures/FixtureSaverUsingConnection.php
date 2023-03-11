<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures;

use Kishlin\Backend\Persistence\Core\Connection\Connection;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\Tools;
use ReflectionException;

final class FixtureSaverUsingConnection extends FixtureSaver
{
    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    /**
     * @throws ReflectionException
     */
    protected function saveAggregateRoot(AggregateRoot $aggregateRoot): void
    {
        $this->connection->insert($this->computeLocation($aggregateRoot), $aggregateRoot->mappedData());
    }

    /**
     * @throws ReflectionException
     */
    protected function computeLocation(AggregateRoot $entity): string
    {
        return Tools::fromPascalToSnakeCase(Tools::shortClassName($entity));
    }
}
