<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\Tools\Database;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

final class SymfonyPostgresDatabase extends PostgresDatabase
{
    protected ?EntityManagerInterface $entityManager = null;

    public function __construct(
        private KernelInterface $kernel,
    ) {
    }

    protected function entityManager(): EntityManagerInterface
    {
        if (null === $this->entityManager) {
            $entityManager = $this->kernel->getContainer()->get(EntityManagerInterface::class);

            assert($entityManager instanceof EntityManagerInterface);

            $this->entityManager = $entityManager;
        }

        return $this->entityManager;
    }
}
