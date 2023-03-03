<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\StepTypeLabel;
use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\StepTypeIdForLabelRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\StepTypeIdForLabelRepositoryUsingDoctrine
 */
final class StepTypeIdForLabelRepositoryUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    /**
     * @throws NonUniqueResultException
     */
    public function testItCanRetrieveAnId(): void
    {
        $this->loadFixture('motorsport.event.stepType.race');

        $repository = new StepTypeIdForLabelRepositoryUsingDoctrine($this->entityManager());

        $expected = self::fixtureId('motorsport.event.stepType.race');
        $actual   = $repository->idForLabel(new StepTypeLabel('race'));

        self::assertEqualsCanonicalizing($expected, $actual);
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
