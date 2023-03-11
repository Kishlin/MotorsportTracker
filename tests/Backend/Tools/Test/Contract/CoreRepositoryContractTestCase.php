<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Test\Contract;

use Kishlin\Backend\Country\Shared\Infrastructure\Persistence\Fixtures\CountryFixtureConverterConfigurator;
use Kishlin\Backend\MotorsportTracker\Shared\Infrastructure\Persistence\Fixtures\MotorsportTrackerFixtureConverterConfigurator;
use Kishlin\Backend\Persistence\Core\Connection\Connection;
use Kishlin\Backend\Persistence\PDO\PDOConnection;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureSaver;

abstract class CoreRepositoryContractTestCase extends RepositoryContractTestCase
{
    protected static function fixturesFolder(): string
    {
        return '/app/etc/Fixtures/Core';
    }

    protected static function configureFixtureSaver(FixtureSaver $fixtureSaver): void
    {
        CountryFixtureConverterConfigurator::populateFixtureSaverWithConverters($fixtureSaver);
        MotorsportTrackerFixtureConverterConfigurator::populateFixtureSaverWithConverters($fixtureSaver);
    }

    protected static function createConnection(): Connection
    {
        return PDOConnection::create(
            $_ENV['DB_HOST'],
            (int) $_ENV['DB_PORT'],
            $_ENV['DB_CORE_TEST'],
            $_ENV['DB_USER'],
            $_ENV['DB_PASSWORD'],
        );
    }
}
