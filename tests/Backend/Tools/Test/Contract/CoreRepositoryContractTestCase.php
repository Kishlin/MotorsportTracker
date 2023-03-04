<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Test\Contract;

use Doctrine\ORM\EntityManagerInterface;
use Kishlin\Backend\Country\Shared\Infrastructure\Persistence\Fixtures\CountryFixtureConverterConfigurator;
use Kishlin\Backend\MotorsportTracker\Shared\Infrastructure\Persistence\Doctrine\EntityManagerFactory\MotorsportTrackerEntityManagerFactory;
use Kishlin\Backend\MotorsportTracker\Shared\Infrastructure\Persistence\Fixtures\MotorsportTrackerFixtureConverterConfigurator;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureSaver;
use Throwable;

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

    protected static function createEntityManager(): EntityManagerInterface
    {
        try {
            return MotorsportTrackerEntityManagerFactory::create(
                ['url' => $_ENV['DATABASE_CORE_URL']],
                'core',
                'test'
            );
        } catch (Throwable $e) {
            self::fail('Failed to create an entity manager: ' . $e->getMessage());
        }
    }
}
