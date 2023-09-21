<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Infrastructure\Loader;

use Kishlin\Backend\MotorsportETL\Shared\Application\Loader;
use Kishlin\Backend\MotorsportETL\Shared\Infrastructure\Repository\EntityRepository;
use Kishlin\Backend\MotorsportETL\Shared\Infrastructure\Repository\UniquenessCheckRepository;
use Kishlin\Backend\Shared\Domain\Entity\Entity;
use Psr\Log\LoggerInterface;

final readonly class LoaderUsingRepositories implements Loader
{
    public function __construct(
        private UniquenessCheckRepository $uniquenessCheckRepository,
        private EntityRepository $entityRepository,
        private ?LoggerInterface $logger = null,
    ) {
    }

    public function load(Entity $entity): void
    {
        if ($this->uniquenessCheckRepository->alreadyExists($entity)) {
            $this->logger?->debug('Entity already exists, skipping', ['entity' => $entity]);

            return;
        }

        $this->logger?->debug('Entity is unique, persisting', ['entity' => $entity]);
        $this->entityRepository->save($entity);
    }
}
