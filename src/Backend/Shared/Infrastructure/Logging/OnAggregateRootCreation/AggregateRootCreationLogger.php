<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Logging\OnAggregateRootCreation;

use Kishlin\Backend\Persistence\Core\Connection\Connection;
use Kishlin\Backend\Persistence\SQL\SQLQuery;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;
use Throwable;

abstract class AggregateRootCreationLogger implements EventSubscriber
{
    public function __construct(
        private readonly Connection $connection,
        private readonly LoggerInterface $logger,
    ) {
    }

    protected function logCreation(string $table, string $id, string $title): void
    {
        /** @noinspection SqlResolve */
        $query = SQLQuery::create("SELECT * FROM {$table} WHERE id = :id;", ['id' => $id]);

        try {
            $data = $this->connection->execute($query)->fetchAllAssociative();
            assert(is_array($data));

            $this->logger->info("Saved {$title}.", $data);
        } catch (Throwable) {
            $this->logger->warning("Unable to log creation of {$title} [{$id}].");
        }
    }
}
