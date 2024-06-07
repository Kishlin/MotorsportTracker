<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Monitoring\Probe;

use Kishlin\Backend\Persistence\Core\Connection\Connection;
use Throwable;

final class DatabaseProbe implements Probe
{
    public function __construct(
        private readonly Connection $connection,
        private readonly string $dbName,
    ) {}

    public function name(): string
    {
        return $this->dbName;
    }

    public function isAlive(): bool
    {
        try {
            $this->connection->connect();
        } catch (Throwable) {
            return false;
        }

        return $this->connection->isConnected();
    }
}
