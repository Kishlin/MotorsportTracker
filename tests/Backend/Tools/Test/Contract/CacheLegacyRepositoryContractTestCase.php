<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Test\Contract;

use Doctrine\ORM\EntityManagerInterface;
use Kishlin\Backend\MotorsportCache\Shared\Infrastructure\Persistence\Fixtures\MotorsportCacheFixtureConverterConfigurator;
use Kishlin\Backend\MotorsportTracker\Shared\Infrastructure\Persistence\Doctrine\EntityManagerFactory\MotorsportTrackerEntityManagerFactory;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureSaver;
use Throwable;

abstract class CacheLegacyRepositoryContractTestCase extends LegacyRepositoryContractTestCase
{
    protected static function fixturesFolder(): string
    {
        return '/app/etc/Fixtures/Cache';
    }

    protected static function configureFixtureSaver(FixtureSaver $fixtureSaver): void
    {
        MotorsportCacheFixtureConverterConfigurator::populateFixtureSaverWithConverters($fixtureSaver);
    }

    protected static function createEntityManager(): EntityManagerInterface
    {
        try {
            return MotorsportTrackerEntityManagerFactory::create(
                ['url' => $_ENV['DATABASE_CACHE_URL']],
                'cache',
                'test'
            );
        } catch (Throwable $e) {
            self::fail('Failed to create an entity manager: ' . $e->getMessage());
        }
    }
}
