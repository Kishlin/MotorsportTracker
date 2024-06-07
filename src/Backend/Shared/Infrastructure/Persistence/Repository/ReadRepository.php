<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Persistence\Repository;

use Kishlin\Backend\Persistence\Core\Connection\Connection;
use Kishlin\Backend\Persistence\Core\QueryBuilder\QueryBuilder;

abstract readonly class ReadRepository
{
    public function __construct(
        protected Connection $connection,
    ) {}

    protected function createQueryBuilder(): QueryBuilder
    {
        return $this->connection->createQueryBuilder();
    }
}
