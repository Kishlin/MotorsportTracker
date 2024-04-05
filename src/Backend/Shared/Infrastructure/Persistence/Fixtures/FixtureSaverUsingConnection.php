<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures;

use Kishlin\Backend\Persistence\Core\Connection\Connection;

final readonly class FixtureSaverUsingConnection implements FixtureSaver
{
    public function __construct(
        private Connection $connection,
    ) {
    }

    public function save(string $class, string $identifier, array $data): void
    {
        $this->connection->insert(
            $class,
            $data,
        );
    }
}
