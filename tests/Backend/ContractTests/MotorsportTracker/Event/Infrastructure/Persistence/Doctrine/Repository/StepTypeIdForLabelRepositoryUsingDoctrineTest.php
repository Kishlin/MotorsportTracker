<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\StepTypeLabel;
use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\StepTypeIdForLabelRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Provider\Event\StepTypeProvider;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\StepTypeIdForLabelRepositoryUsingDoctrine
 */
final class StepTypeIdForLabelRepositoryUsingDoctrineTest extends RepositoryContractTestCase
{
    /**
     * @throws NonUniqueResultException
     */
    public function testItCanRetrieveAnId(): void
    {
        $stepType = StepTypeProvider::race();

        $this->loadFixtures($stepType);

        $repository = new StepTypeIdForLabelRepositoryUsingDoctrine($this->entityManager());

        self::assertEqualsCanonicalizing($stepType->id(), $repository->idForLabel(($stepType->label())));
    }

    /**
     * @throws NonUniqueResultException
     */
    public function testItReturnsNullIfLabelDoesNotExist(): void
    {
        $repository = new StepTypeIdForLabelRepositoryUsingDoctrine($this->entityManager());

        self::assertNull($repository->idForLabel(new StepTypeLabel('race')));
    }
}
