<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Test\Contract;

use Exception;
use Kishlin\Backend\Persistence\Core\Connection\Connection;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureLoader;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureSaver;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureSaverUsingConnection;
use Kishlin\Backend\Shared\Infrastructure\Randomness\UuidGeneratorUsingRamsey;
use Kishlin\Tests\Backend\Tools\Test\Contract\Constraint\AggregateRootWasSavedConstraint;
use Kishlin\Tests\Backend\Tools\Test\Contract\Constraint\TableIsEmptyConstraint;
use PHPUnit\Framework\TestCase;

/**
 * Abstract TestCase for Contract Tests of Repositories, child classes of \Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\Repository.
 *
 * @internal
 * @covers \Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CacheRepository
 * @covers \Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository
 * @covers \Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\Repository
 */
abstract class RepositoryContractTestCase extends TestCase
{
    private static ?Connection $connection = null;

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

        if (null !== self::$connection) {
            self::$connection->close();

            self::$connection = null;
        }
    }

    protected function setUp(): void
    {
        self::connection()->beginTransaction();
    }

    protected function tearDown(): void
    {
        self::$connection?->rollback();
        self::$fixtureLoader?->reset();
    }

    public static function assertAggregateRootWasSaved(AggregateRoot $aggregateRoot): void
    {
        self::assertThat($aggregateRoot, new AggregateRootWasSavedConstraint(self::connection()));
    }

    /**
     * @param class-string<object>|object $entity
     */
    public static function assertTableIsEmpty(object|string $entity): void
    {
        self::assertThat($entity, new TableIsEmptyConstraint(self::connection()));
    }

    abstract protected static function fixturesFolder(): string;

    abstract protected static function createConnection(): Connection;

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
        } catch (Exception $e) {
            var_dump($e->getTrace());
            self::fail("Failed to load fixture: {$e->getMessage()}");
        }
    }

    protected static function fixtureId(string $fixture): string
    {
        return self::fixtureLoader()->identifier($fixture);
    }

    protected static function connection(): Connection
    {
        if (null === self::$connection) {
            self::$connection = static::createConnection();
        }

        return self::$connection;
    }

    private static function fixtureSaver(): FixtureSaver
    {
        if (null === self::$fixtureSaver) {
            self::$fixtureSaver = new FixtureSaverUsingConnection(self::connection());
        }

        return self::$fixtureSaver;
    }

    private static function fixtureLoader(): FixtureLoader
    {
        if (null === self::$fixtureLoader) {
            self::$fixtureLoader = new FixtureLoader(self::uuidGenerator(), self::fixtureSaver(), static::fixturesFolder());
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
}
