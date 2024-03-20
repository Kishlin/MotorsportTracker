<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Test\Contract;

use Kishlin\Backend\Persistence\Core\Connection\Connection;
use Kishlin\Backend\Persistence\PDO\PDOConnection;

abstract class CacheRepositoryContractTestCase extends RepositoryContractTestCase
{
    protected static function fixturesFolder(): string
    {
        return '/app/etc/Fixtures/Cache';
    }

    protected static function createConnection(): Connection
    {
        return PDOConnection::create(
            $_ENV['DB_HOST'],
            (int) $_ENV['DB_PORT'],
            $_ENV['DB_CACHE_TEST'],
            $_ENV['DB_USER'],
            $_ENV['DB_PASSWORD'],
        );
    }
}
