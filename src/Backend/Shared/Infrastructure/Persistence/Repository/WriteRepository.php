<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Persistence\Repository;

use Kishlin\Backend\Persistence\Core\Connection\Connection;

abstract readonly class WriteRepository
{
    public function __construct(
        protected Connection $connection,
    ) {}

    /**
     * @param array<string, null|bool|float|int|string> $data
     */
    protected function persist(string $location, array $data): void
    {
        $this->connection->insert($location, $data);
    }
}
