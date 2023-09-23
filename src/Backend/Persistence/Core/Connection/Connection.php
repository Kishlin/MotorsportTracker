<?php

declare(strict_types=1);

namespace Kishlin\Backend\Persistence\Core\Connection;

use Kishlin\Backend\Persistence\Core\Query\Query;
use Kishlin\Backend\Persistence\Core\QueryBuilder\QueryBuilder;
use Kishlin\Backend\Persistence\Core\Result\Result;

interface Connection
{
    public function createQueryBuilder(): QueryBuilder;

    /**
     * @param array<string, null|bool|float|int|string> $data
     */
    public function insert(string $table, array $data): Result;

    /**
     * @param array<string, null|bool|float|int|string> $data
     */
    public function update(string $table, string $id, array $data): Result;

    public function execute(Query $query): Result;

    public function beginTransaction(): void;

    public function rollback(): void;

    public function connect(): void;

    public function close(): void;

    public function isConnected(): bool;
}
