<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Database;

use Doctrine\DBAL\Exception as DoctrineException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureLoader;

final class PostgresDatabase implements DatabaseInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly FixtureLoader $fixtureLoader,
    ) {
    }

    /**
     * @throws DoctrineException
     */
    public function refreshDatabase(): void
    {
        $this->entityManager->clear();
        $this->fixtureLoader->reset();

        // Disables all triggers and foreign key checks
        $this->entityManager->getConnection()->executeQuery("SET session_replication_role = 'replica';");

        $listTableNames = $this->entityManager->getConnection()->createSchemaManager()->listTableNames();

        foreach ($listTableNames as $tableName) {
            $sql = "DELETE FROM {$tableName}";
            $this->entityManager->getConnection()->executeQuery($sql);
        }

        $this->entityManager->getConnection()->executeQuery("SET session_replication_role = 'origin';");
    }

    /**
     * @throws Exception
     */
    public function loadFixture(string $fixture): void
    {
        $this->fixtureLoader->loadFixture($fixture);
    }

    public function fixtureId(string $fixture): string
    {
        return $this->fixtureLoader->identifier($fixture);
    }

    /**
     * @throws DoctrineException
     */
    public function fetchOne(string $query, array $params = []): mixed
    {
        $result = $this->entityManager->getConnection()->fetchOne($query, $params);

        return false !== $result ? $result : null;
    }

    /**
     * @throws DoctrineException
     */
    public function fetchAssociative(string $query, array $params = []): array|null
    {
        return $this->entityManager->getConnection()->fetchAssociative($query, $params) ?: null;
    }

    /**
     * @throws DoctrineException
     */
    public function fetchAllAssociative(string $query, array $params = []): array|null
    {
        return $this->entityManager->getConnection()->fetchAllAssociative($query, $params) ?: null;
    }

    /**
     * @throws DoctrineException
     */
    public function exec(string $query, array $params = []): void
    {
        $this->entityManager->getConnection()->executeStatement($query, $params);
    }

    public function close(): void
    {
        $this->entityManager->getConnection()->close();
    }
}
