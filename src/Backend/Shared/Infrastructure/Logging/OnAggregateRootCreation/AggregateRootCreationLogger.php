<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Logging\OnAggregateRootCreation;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

abstract class AggregateRootCreationLogger implements EventSubscriber
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly LoggerInterface $logger,
    ) {
    }

    protected function logCreation(string $table, string $id, string $title): void
    {
        try {
            /** @noinspection SqlResolve */
            $data = $this->em->getConnection()->fetchAssociative("SELECT * FROM {$table} WHERE id = :id", ['id' => $id]);
            assert(is_array($data));

            $this->logger->info("Saved {$title}.", $data);
        } catch (Exception) {
            $this->logger->warning("Unable to log creation of {$title} [{$id}].");
        }
    }
}
