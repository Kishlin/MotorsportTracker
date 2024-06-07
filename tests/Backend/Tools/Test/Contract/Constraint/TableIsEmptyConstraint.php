<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Test\Contract\Constraint;

use Kishlin\Backend\Persistence\Core\Connection\Connection;
use Kishlin\Backend\Persistence\SQL\SQLQuery;
use Kishlin\Backend\Shared\Domain\Tools;
use PHPUnit\Framework\Constraint\Constraint;
use ReflectionException;
use RuntimeException;

final class TableIsEmptyConstraint extends Constraint
{
    public function __construct(
        private readonly Connection $connection,
    ) {}

    public function toString(): string
    {
        return ' is an empty table.';
    }

    /**
     * @param class-string<object>|object $other
     *
     * @throws ReflectionException
     */
    protected function matches($other): bool
    {
        $table = $this->computeLocation($other);

        $result = $this->connection->execute(SQLQuery::create("SELECT count(id) as count FROM {$table};"));

        if ($result->isFail()) {
            throw new RuntimeException("Failed to ensure {$table} is empty.");
        }

        return 0 === $result->fetchAllAssociative()[0]['count'];
    }

    /**
     * @param class-string<object>|object $entity
     *
     * @throws ReflectionException
     */
    protected function computeLocation(object|string $entity): string
    {
        return Tools::fromPascalToSnakeCase(Tools::shortClassName($entity));
    }
}
