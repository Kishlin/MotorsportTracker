<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures;

use Doctrine\ORM\EntityManagerInterface;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;

final class FixtureSaverUsingDoctrine extends FixtureSaver
{
    /** @param FixtureConverter[] $fixturesConverters */
    public function __construct(
        private EntityManagerInterface $entityManager,
        array $fixturesConverters,
    ) {
        parent::__construct($fixturesConverters);
    }

    protected function saveAggregateRoot(AggregateRoot $aggregateRoot): void
    {
        $this->entityManager->persist($aggregateRoot);
        $this->entityManager->flush();
    }
}
