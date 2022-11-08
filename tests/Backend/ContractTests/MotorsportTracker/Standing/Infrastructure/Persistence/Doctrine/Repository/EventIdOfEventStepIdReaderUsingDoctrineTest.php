<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepId;
use Kishlin\Backend\MotorsportTracker\Standing\Application\RefreshStandingsOnResultsRecorded\EventNotFoundForEventStepException;
use Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\Repository\EventIdOfEventStepIdReaderUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\Repository\EventIdOfEventStepIdReaderUsingDoctrine
 */
final class EventIdOfEventStepIdReaderUsingDoctrineTest extends RepositoryContractTestCase
{
    /**
     * @throws Exception
     */
    public function testItCanRetrieveTheId(): void
    {
        self::loadFixture('motorsport.event.eventStep.dutchGrandPrix2022Race');

        $repository = new EventIdOfEventStepIdReaderUsingDoctrine(self::entityManager());

        $result = $repository->eventIdForEventStepId(
            new EventStepId(self::fixtureId('motorsport.event.eventStep.dutchGrandPrix2022Race')),
        );

        self::assertSame(self::fixtureId('motorsport.event.event.dutchGrandPrix2022'), $result);
    }

    /**
     * @throws Exception
     */
    public function testItCanRetrieveTheIdFromAnyStep(): void
    {
        self::loadFixtures(
            'motorsport.event.eventStep.emiliaRomagnaGrandPrix2022SprintQualifying',
            'motorsport.event.eventStep.emiliaRomagnaGrandPrix2022Race',
        );

        $repository = new EventIdOfEventStepIdReaderUsingDoctrine(self::entityManager());

        $expected = self::fixtureId('motorsport.event.event.emiliaRomagnaGrandPrix2022');

        self::assertSame(
            $expected,
            $repository->eventIdForEventStepId(
                new EventStepId(self::fixtureId('motorsport.event.eventStep.emiliaRomagnaGrandPrix2022SprintQualifying')),
            ),
        );

        self::assertSame(
            $expected,
            $repository->eventIdForEventStepId(
                new EventStepId(self::fixtureId('motorsport.event.eventStep.emiliaRomagnaGrandPrix2022Race')),
            ),
        );
    }

    /**
     * @throws Exception
     */
    public function testItThrowsAnExceptionIfNotExists(): void
    {
        $repository = new EventIdOfEventStepIdReaderUsingDoctrine(self::entityManager());

        self::expectException(EventNotFoundForEventStepException::class);

        $repository->eventIdForEventStepId(new EventStepId('43edfb71-dad7-415c-bd2b-bf4933e51293'));
    }
}
