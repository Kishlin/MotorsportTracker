<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepEventId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepTypeId;
use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\EventHasStepWithTypeRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\EventHasStepWithTypeRepositoryUsingDoctrine
 */
final class EventHasStepWithTypeRepositoryUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    /**
     * @throws NonUniqueResultException
     */
    public function testItReturnsFalseIfNoEventStepExists(): void
    {
        $repository = new EventHasStepWithTypeRepositoryUsingDoctrine($this->entityManager());

        self::assertFalse($repository->eventHasStepWithType(
            new EventStepEventId(self::uuid()),
            new EventStepTypeId(self::uuid()),
        ));
    }

    /**
     * @throws NonUniqueResultException
     */
    public function testItReturnsTrueIfAnEventStepExistsWithTheType(): void
    {
        self::loadFixture('motorsport.event.eventStep.dutchGrandPrix2022Race');

        $repository = new EventHasStepWithTypeRepositoryUsingDoctrine($this->entityManager());

        self::assertTrue($repository->eventHasStepWithType(
            new EventStepEventId(self::fixtureId('motorsport.event.event.dutchGrandPrix2022')),
            new EventStepTypeId(self::fixtureId('motorsport.event.stepType.race')),
        ));
    }
}
