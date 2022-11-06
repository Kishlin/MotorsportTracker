<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\Tools\Database;

use Doctrine\DBAL\Exception as DoctrineException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Kishlin\Backend\Country\Shared\Infrastructure\Persistence\Fixtures\CountryFixtureConverterConfigurator;
use Kishlin\Backend\MotorsportTracker\Shared\Infrastructure\Persistence\Fixtures\MotorsportTrackerFixtureConverterConfigurator;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureLoader;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureSaverUsingDoctrine;
use Kishlin\Backend\Shared\Infrastructure\Randomness\UuidGeneratorUsingRamsey;
use RuntimeException;

abstract class PostgresDatabase implements DatabaseInterface
{
    private ?FixtureLoader $fixtureLoader = null;

    /**
     * @throws DoctrineException
     */
    public function refreshDatabase(): void
    {
        $this->entityManager()->clear();
        $this->fixtureLoader()->reset();

        // Disables all triggers and foreign key checks
        $this->entityManager()->getConnection()->executeQuery("SET session_replication_role = 'replica';");

        $listTableNames = $this->entityManager()->getConnection()->createSchemaManager()->listTableNames();

        foreach ($listTableNames as $tableName) {
            $sql = "DELETE FROM {$tableName}";
            $this->entityManager()->getConnection()->executeQuery($sql);
        }

        $this->entityManager()->getConnection()->executeQuery("SET session_replication_role = 'origin';");
    }

    /**
     * @throws Exception
     */
    public function loadFixture(string $fixture): void
    {
        $this->fixtureLoader()->loadFixture($fixture);
    }

    public function fixtureId(string $fixture): string
    {
        $identifier = $this->fixtureLoader()->identifier($fixture);

        if (null === $identifier) {
            throw new RuntimeException("Fixture {$fixture} appears to not have been loaded.");
        }

        return $identifier;
    }

    /**
     * @throws DoctrineException
     */
    public function fetchOne(string $query, array $params = []): mixed
    {
        $result = $this->entityManager()->getConnection()->fetchOne($query, $params);

        return false !== $result ? $result : null;
    }

    /**
     * @throws DoctrineException
     */
    public function fetchAssociative(string $query, array $params = []): array|null
    {
        return $this->entityManager()->getConnection()->fetchAssociative($query, $params) ?: null;
    }

    /**
     * @throws DoctrineException
     */
    public function fetchAllAssociative(string $query, array $params = []): array|null
    {
        return $this->entityManager()->getConnection()->fetchAllAssociative($query, $params) ?: null;
    }

    /**
     * @throws DoctrineException
     */
    public function exec(string $query, array $params = []): void
    {
        $this->entityManager()->getConnection()->executeStatement($query, $params);
    }

    abstract protected function entityManager(): EntityManagerInterface;

    private function fixtureLoader(): FixtureLoader
    {
        if (null === $this->fixtureLoader) {
            $fixtureSaver = new FixtureSaverUsingDoctrine($this->entityManager());

            CountryFixtureConverterConfigurator::populateFixtureSaverWithConverters($fixtureSaver);
            MotorsportTrackerFixtureConverterConfigurator::populateFixtureSaverWithConverters($fixtureSaver);

            $this->fixtureLoader = new FixtureLoader(new UuidGeneratorUsingRamsey(), $fixtureSaver, '/app/etc/Fixtures');
        }

        return $this->fixtureLoader;
    }
}
