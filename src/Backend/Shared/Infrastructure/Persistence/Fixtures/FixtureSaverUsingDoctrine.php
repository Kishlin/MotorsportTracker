<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures;

use Doctrine\ORM\EntityManagerInterface;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;

final class FixtureSaverUsingDoctrine extends FixtureSaver
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    protected function saveAggregateRoot(AggregateRoot $aggregateRoot): void
    {
        $this->entityManager->persist($aggregateRoot);
        $this->entityManager->flush();
    }
}
