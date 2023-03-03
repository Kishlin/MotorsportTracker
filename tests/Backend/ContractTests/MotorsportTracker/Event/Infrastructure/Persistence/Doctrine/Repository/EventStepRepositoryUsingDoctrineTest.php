<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\EventStep;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepDateTime;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepEventId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepTypeId;
use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\EventStepRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\EventStepRepositoryUsingDoctrine
 */
final class EventStepRepositoryUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    public function testItCanSaveAnEventStep(): void
    {
        self::loadFixtures(
            'motorsport.event.event.dutchGrandPrix2022',
            'motorsport.event.stepType.race',
        );

        $eventStep = EventStep::instance(
            new EventStepId(self::uuid()),
            new EventStepTypeId(self::fixtureId('motorsport.event.stepType.race')),
            new EventStepEventId(self::fixtureId('motorsport.event.event.dutchGrandPrix2022')),
            new EventStepDateTime(new DateTimeImmutable('2022-09-04 15:00:00')),
        );

        $repository = new EventStepRepositoryUsingDoctrine(self::entityManager());

        $repository->save($eventStep);

        self::assertAggregateRootWasSaved($eventStep);
    }
}
