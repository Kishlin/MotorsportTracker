<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Infrastructure\CachedConnector;

use Kishlin\Backend\Persistence\SQL\SQLQuery;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\ClientRepositoryInterface;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\WriteRepository;

final readonly class CacheInvalidatorRepository extends WriteRepository implements ClientRepositoryInterface
{
    private const QUERY = <<<'SQL'
DELETE FROM %s
WHERE key = :key
SQL;

    public function invalidate(string $table, string $key): void
    {
        $this->connection->execute(
            SQLQuery::create(
                sprintf(self::QUERY, $table),
                ['key' => $key],
            ),
        );
    }
}
