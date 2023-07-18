<?php

declare(strict_types=1);

namespace Kishlin\Backend\Persistence\PDO;

use Kishlin\Backend\Persistence\Core\Connection\Connection;
use Kishlin\Backend\Persistence\Core\Query\Query;
use Kishlin\Backend\Persistence\Core\Result\ResultFailure;
use Kishlin\Backend\Persistence\SQL\SQLQueryBuilder;
use PDO;
use Throwable;

final class PDOConnection implements Connection
{
    private ?PDO $pdo = null;

    private function __construct(
        private readonly string $host,
        private readonly int $port,
        private readonly string $dbName,
        private readonly string $user,
        private readonly string $password,
    ) {
    }

    public function createQueryBuilder(): SQLQueryBuilder
    {
        return new SQLQueryBuilder();
    }

    public function insert(string $table, array $data): PDOResult
    {
        try {
            $connection = $this->connection();
        } catch (Throwable) {
            return PDOResult::fail(ResultFailure::CONNECTION_ERROR);
        }

        $query = "INSERT INTO {$table} (\""
            . implode('", "', array_keys($data))
            . '") VALUES (:'
            . implode(', :', array_keys($data))
            . ');';

        $statement = $connection->prepare($query);

        $status = $statement->execute($data);

        return false === $status ? PDOResult::fail(ResultFailure::QUERY_ERROR) : PDOResult::ok($statement);
    }

    public function execute(Query $query): PDOResult
    {
        try {
            $connection = $this->connection();
        } catch (Throwable) {
            return PDOResult::fail(ResultFailure::CONNECTION_ERROR);
        }

        $statement = $connection->prepare($query->query());

        $status = $statement->execute($query->parameters());

        if (false === $status) {
            return PDOResult::fail(ResultFailure::QUERY_ERROR);
        }

        return PDOResult::ok($statement);
    }

    /**
     * @throws Throwable
     */
    public function beginTransaction(): void
    {
        $this->connection()->beginTransaction();
    }

    /**
     * @throws Throwable
     */
    public function rollback(): void
    {
        $this->connection()->rollBack();
    }

    /**
     * @throws Throwable
     */
    public function connect(): void
    {
        $this->pdo = new PDO(
            "pgsql:host={$this->host};port={$this->port};dbname={$this->dbName};",
            $this->user,
            $this->password,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION],
        );
    }

    public function close(): void
    {
        if (null !== $this->pdo) {
            $this->pdo = null;
        }
    }

    public function isConnected(): bool
    {
        return null !== $this->pdo;
    }

    public static function create(string $host, int $port, string $dbName, string $user, string $password): self
    {
        return new self($host, $port, $dbName, $user, $password);
    }

    /**
     * @throws Throwable
     */
    private function connection(): PDO
    {
        if (null === $this->pdo) {
            $this->connect();
        }

        assert(null !== $this->pdo);

        return $this->pdo;
    }
}
