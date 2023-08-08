<?php

declare(strict_types=1);

namespace Kishlin\Backend\Persistence\Migration;

use Kishlin\Backend\Persistence\Core\Connection\Connection;
use Kishlin\Backend\Persistence\SQL\SQLQuery;
use Psr\Log\LoggerInterface;

final readonly class Migrator
{
    private const DELETE_VERSION = 'DELETE FROM migration_version WHERE version = :version';

    public function __construct(
        private LoggerInterface $logger,
        private Connection $connection,
        private string $namespace,
        private string $table,
        private string $folder,
    ) {
    }

    public function upALl(): void
    {
        $content = scandir($this->folder);

        if (false === $content) {
            $this->logger->error("Failed to scan content of directory {$this->folder}");

            return;
        }

        foreach ($content as $file) {
            if (str_ends_with($file, '.php')) {
                $this->upOne(substr($file, 0, strlen($file) - 4));
            }
        }
    }

    public function downALl(): void
    {
        $content = scandir($this->folder);

        if (false === $content) {
            $this->logger->error("Failed to scan content of directory {$this->folder}");

            return;
        }

        foreach ($content as $file) {
            if (str_ends_with($file, '.php')) {
                $this->downOne(substr($file, 0, strlen($file) - 4));
            }
        }
    }

    public function upOne(string $version): void
    {
        if ($this->hasMigratedVersion($version)) {
            $this->logger->info("Did not apply migration {$version} as it was already applied.");

            return;
        }

        $migrationClass = $this->migrationFromFile($version);

        foreach (explode(';', $migrationClass->up()) as $statement) {
            $this->applyStatement($statement);
        }

        $this->flagVersionAsMigrated($version);

        $this->logger->info("Successfully applied migration {$version}");
    }

    public function downOne(string $version): void
    {
        if (false === $this->hasMigratedVersion($version)) {
            $this->logger->info("Cannot down missing migration {$version}.");

            return;
        }

        $migrationClass = $this->migrationFromFile($version);

        foreach (explode(';', $migrationClass->down()) as $statement) {
            $this->applyStatement($statement);
        }

        $this->removeFlagForVersion($version);

        $this->logger->info("Successfully downed migration {$version}");
    }

    private function migrationFromFile(string $version): Migration
    {
        require_once "{$this->folder}/{$version}.php";

        $fullyQualifiedClassName = "{$this->namespace}\\{$version}";

        $migrationClass = new $fullyQualifiedClassName();
        assert($migrationClass instanceof Migration);

        return $migrationClass;
    }

    private function applyStatement(string $statement): void
    {
        $trimmed = trim($statement);

        if (empty($trimmed)) {
            return;
        }

        $this->connection->execute(SQLQuery::create($trimmed));

        $this->logger->debug("Applied query `{$trimmed}`");
    }

    private function hasMigratedVersion(string $version): bool
    {
        $qb = $this->connection->createQueryBuilder();

        $query = $qb
            ->select('migrated_on')
            ->from($this->table)
            ->where($qb->expr()->eq('version', ':version'))
            ->withParam('version', $version)
            ->buildQuery()
        ;

        $ret = $this->connection->execute($query)->fetchOne();

        return is_string($ret);
    }

    private function flagVersionAsMigrated(string $version): void
    {
        $this->connection->insert($this->table, ['version' => $version, 'migrated_on' => date('c')]);
    }

    private function removeFlagForVersion(string $version): void
    {
        $this->connection->execute(SQLQuery::create(self::DELETE_VERSION, ['version' => $version]));
    }
}
