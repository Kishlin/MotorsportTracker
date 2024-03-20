<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Test\Contract;

use Kishlin\Backend\Persistence\Core\Connection\Connection;
use Kishlin\Backend\Persistence\PDO\PDOConnection;

abstract class CoreRepositoryContractTestCase extends RepositoryContractTestCase
{
    protected static function fixturesFolder(): string
    {
        return '/app/etc/Fixtures/Core';
    }

    protected static function createConnection(): Connection
    {
        return PDOConnection::create(
            $_ENV['DB_CORE_HOST'],
            (int) $_ENV['DB_CORE_PORT'],
            $_ENV['DB_CORE_NAME'],
            $_ENV['DB_CORE_USER'],
            $_ENV['DB_CORE_PASSWORD'],
        );
    }
}
