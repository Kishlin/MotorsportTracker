<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Database;

use Exception;
use Kishlin\Backend\Persistence\Core\Connection\Connection;
use Kishlin\Backend\Persistence\SQL\SQLQuery;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureLoader;

final class PostgresDatabase implements DatabaseInterface
{
    public function __construct(
        private readonly Connection $connection,
        private readonly FixtureLoader $fixtureLoader,
    ) {
    }

    public function refreshDatabase(): void
    {
        $this->fixtureLoader->reset();

        // Disables all triggers and foreign key checks
        $this->connection->execute(SQLQuery::create("SET session_replication_role = 'replica';"));

        $query = SQLQuery::create("SELECT TABLE_NAME FROM information_schema.tables WHERE table_schema = 'public';");

        /** @var array<array{table_name: string}> $tables */
        $tables = $this->connection->execute($query)->fetchAllAssociative();

        foreach ($tables as $table) {
            $this->connection->execute(SQLQuery::create("TRUNCATE TABLE {$table['table_name']};"));
        }

        $this->connection->execute(SQLQuery::create("SET session_replication_role = 'origin';"));
    }

    /**
     * @throws Exception
     */
    public function loadFixture(string $fixture): void
    {
        $this->fixtureLoader->loadFixture($fixture);
    }

    public function fixtureId(string $fixture): string
    {
        return $this->fixtureLoader->identifier($fixture);
    }

    /**
     * {@inheritDoc}
     */
    public function fetchOne(string $query, array $params = []): mixed
    {
        return $this->connection->execute(SQLQuery::create($query, $params))->fetchOne();
    }

    /**
     * {@inheritDoc}
     */
    public function fetchAssociative(string $query, array $params = []): array|null
    {
        return $this->connection->execute(SQLQuery::create($query, $params))->fetchAssociative() ?: null;
    }

    /**
     * {@inheritDoc}
     */
    public function fetchAllAssociative(string $query, array $params = []): array|null
    {
        return $this->connection->execute(SQLQuery::create($query, $params))->fetchAllAssociative() ?: null;
    }

    /**
     * {@inheritDoc}
     */
    public function exec(string $query, array $params = []): void
    {
        $this->connection->execute(SQLQuery::create($query, $params));
    }

    public function close(): void
    {
        $this->connection->close();
    }
}
