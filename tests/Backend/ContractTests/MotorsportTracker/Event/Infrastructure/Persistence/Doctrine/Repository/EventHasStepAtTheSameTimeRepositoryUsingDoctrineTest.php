<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository;

use DateTimeImmutable;
use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepDateTime;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepEventId;
use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\EventHasStepAtTheSameTimeRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\EventHasStepAtTheSameTimeRepositoryUsingDoctrine
 */
final class EventHasStepAtTheSameTimeRepositoryUsingDoctrineTest extends RepositoryContractTestCase
{
    /**
     * @throws NonUniqueResultException
     */
    public function testItReturnsFalseIfNoEventStepExists(): void
    {
        $repository = new EventHasStepAtTheSameTimeRepositoryUsingDoctrine($this->entityManager());

        self::assertFalse($repository->eventHasStepAtTheSameTime(
            new EventStepEventId(self::uuid()),
            new EventStepDateTime(new DateTimeImmutable('2022-09-04 15:00:00')),
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
            new EventStepDateTime(new DateTimeImmutable('2022-09-04 15:00:00')),
        ));
    }
}
