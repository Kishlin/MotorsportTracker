<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Test\Contract\Constraint;

use Kishlin\Backend\Persistence\Core\Connection\Connection;
use Kishlin\Backend\Persistence\SQL\SQLQuery;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\Tools;
use PHPUnit\Framework\Constraint\Constraint;
use ReflectionException;
use RuntimeException;

final class AggregateRootWasSavedConstraint extends Constraint
{
    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    public function toString(): string
    {
        return ' was saved to the database.';
    }

    /**
     * @param object $other
     *
     * @throws ReflectionException
     */
    protected function matches($other): bool
    {
        assert($other instanceof AggregateRoot);

        if (false === method_exists($other, 'id')) {
            return false;
        }

        $id = $other->id();

        $table = $this->computeLocation($other);

        $result = $this->connection->execute(SQLQuery::create("SELECT count(id) as count FROM {$table} WHERE id = '{$id}';"));
        if ($result->isFail()) {
            throw new RuntimeException("Failed to count ids in table {$table}.");
        }

        return 1 === $result->fetchAllAssociative()[0]['count'];
    }

    /**
     * @throws ReflectionException
     */
    protected function computeLocation(AggregateRoot $entity): string
    {
        return Tools::fromPascalToSnakeCase(Tools::shortClassName($entity));
    }
}
