<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\CreateEventStep;

use DateTimeImmutable;
use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepDateTime;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepEventId;
use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\CreateEventStep\EventHasStepAtTheSameTimeRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\CreateEventStep\EventHasStepAtTheSameTimeRepositoryUsingDoctrine
 */
final class EventHasStepAtTheSameTimeRepositoryUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    /**
     * @throws NonUniqueResultException
     */
    public function testItReturnsFalseIfNoEventStepExists(): void
    {
        $repository = new EventHasStepAtTheSameTimeRepositoryUsingDoctrine($this->entityManager());

        self::assertFalse($repository->eventHasStepAtTheSameTime(
            new EventStepEventId(self::uuid()),
            new EventStepDateTime(new DateTimeImmutable('2022-09-04 13:00:00')),
        ));
    }

    /**
     * @throws NonUniqueResultException
     */
    public function testItReturnsTrueIfAnEventStepExistsWithTheSameTime(): void
    {
        self::loadFixture('motorsport.event.eventStep.dutchGrandPrix2022Race');

        $repository = new EventHasStepAtTheSameTimeRepositoryUsingDoctrine($this->entityManager());

        self::assertTrue($repository->eventHasStepAtTheSameTime(
            new EventStepEventId(self::fixtureId('motorsport.event.event.dutchGrandPrix2022')),
            new EventStepDateTime(new DateTimeImmutable('2022-09-04 13:00:00')),
        ));
    }
}
