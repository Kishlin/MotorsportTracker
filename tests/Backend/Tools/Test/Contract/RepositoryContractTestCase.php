<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Test\Contract;

use Doctrine\ORM\EntityManagerInterface;
use Kishlin\Backend\Country\Shared\Infrastructure\Persistence\Fixtures\CountryFixtureConverterConfigurator;
use Kishlin\Backend\MotorsportTracker\Shared\Infrastructure\Persistence\Doctrine\EntityManagerFactory\MotorsportTrackerEntityManagerFactory;
use Kishlin\Backend\MotorsportTracker\Shared\Infrastructure\Persistence\Fixtures\MotorsportTrackerFixtureConverterConfigurator;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureLoader;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureSaver;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureSaverUsingDoctrine;
use Kishlin\Backend\Shared\Infrastructure\Randomness\UuidGeneratorUsingRamsey;
use Kishlin\Tests\Backend\Tools\Test\Contract\Constraint\AggregateRootWasSavedConstraint;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * Abstract TestCase for Contract Tests of Repositories, child classes of \Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository.
 *
 * @internal
 * @covers \Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository
 */
abstract class RepositoryContractTestCase extends TestCase
{
    private const FIXTURES_FOLDER = '/app/etc/Fixtures';

    private static ?EntityManagerInterface $entityManager = null;

    private static ?FixtureLoader $fixtureLoader = null;
    private static ?FixtureSaver $fixtureSaver   = null;

    private static ?UuidGenerator $uuidGenerator = null;

    public static function tearDownAfterClass(): void
    {
        if (null !== self::$fixtureLoader) {
            self::$fixtureLoader = null;
        }

        if (null !== self::$fixtureSaver) {
            self::$fixtureSaver = null;
        }

        if (null !== self::$uuidGenerator) {
            self::$uuidGenerator = null;
        }

        if (null !== self::$entityManager) {
            self::$entityManager->close();

            self::$entityManager = null;
        }
    }

    protected function setUp(): void
    {
        self::entityManager()->beginTransaction();
    }

    protected function tearDown(): void
    {
        self::$entityManager?->rollback();
        self::$fixtureLoader?->reset();
    }

    public static function assertAggregateRootWasSaved(AggregateRoot $aggregateRoot): void
    {
        self::assertThat($aggregateRoot, new AggregateRootWasSavedConstraint(self::entityManager()));
    }

    protected static function uuid(): string
    {
        return self::uuidGenerator()->uuid4();
    }

    protected static function loadFixtures(string ...$fixtures): void
    {
        foreach ($fixtures as $fixture) {
            self::loadFixture($fixture);
        }
    }

    protected static function loadFixture(string $fixture): void
    {
        try {
            self::fixtureLoader()->loadFixture($fixture);
        } catch (\Exception $e) {
            self::fail("Failed to load fixture: {$e->getMessage()}");
        }
    }

    protected static function fixtureId(string $fixture): string
    {
        $identifier = self::fixtureLoader()->identifier($fixture);

        if (null === $identifier) {
            throw new \RuntimeException("Fixture {$fixture} does not appear to have been loaded.");
        }

        return $identifier;
    }

    protected static function execute(string $sql): void
    {
        try {
            self::entityManager()->getConnection()->executeStatement($sql);
        } catch (Throwable $e) {
            self::fail($e->getMessage());
        }
    }

    protected static function entityManager(): EntityManagerInterface
    {
        if (null === self::$entityManager) {
            self::$entityManager = self::createEntityManager();
        }

        return self::$entityManager;
    }

    private static function fixtureSaver(): FixtureSaver
    {
        if (null === self::$fixtureSaver) {
            self::$fixtureSaver = new FixtureSaverUsingDoctrine(self::entityManager());

            CountryFixtureConverterConfigurator::populateFixtureSaverWithConverters(self::$fixtureSaver);
            MotorsportTrackerFixtureConverterConfigurator::populateFixtureSaverWithConverters(self::$fixtureSaver);
        }

        return self::$fixtureSaver;
    }

    private static function fixtureLoader(): FixtureLoader
    {
        if (null === self::$fixtureLoader) {
            self::$fixtureLoader = new FixtureLoader(self::uuidGenerator(), self::fixtureSaver(), self::FIXTURES_FOLDER);
        }

        return self::$fixtureLoader;
    }

    private static function uuidGenerator(): UuidGenerator
    {
        if (null === self::$uuidGenerator) {
            self::$uuidGenerator = new UuidGeneratorUsingRamsey();
        }

        return self::$uuidGenerator;
    }

    private static function createEntityManager(): EntityManagerInterface
    {
        try {
            return MotorsportTrackerEntityManagerFactory::create(
                ['url' => $_ENV['DATABASE_URL']],
                'test'
            );
        } catch (Throwable $e) {
            self::fail('Failed to create an entity manager: ' . $e->getMessage());
        }
    }
}
