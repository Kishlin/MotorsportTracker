<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Calendar\Infrastructure\Persistence\Doctrine\Repository;

use Exception;
use Kishlin\Backend\MotorsportTracker\Calendar\Infrastructure\Persistence\Doctrine\Repository\EventStepViewDataRepositoryUsingDoctrine;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepId;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Calendar\Infrastructure\Persistence\Doctrine\Repository\EventStepViewDataRepositoryUsingDoctrine
 */
final class EventStepViewDataRepositoryUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    /**
     * @throws Exception
     */
    public function testItCannotViewAnEventStepWhichDoesNotExist(): void
    {
        $repository = new EventStepViewDataRepositoryUsingDoctrine(self::entityManager());

        self::assertNull($repository->find(new EventStepId('1fbb8cb4-9c98-40ca-bca8-cd1c2c530c2f')));
    }

    /**
     * @throws Exception
     */
    public function testItCanViewDataForAnEventStep(): void
    {
        $eventStepFixture = 'motorsport.event.eventStep.dutchGrandPrix2022Race';

        self::loadFixtures(
            $eventStepFixture,
            'motorsport.championship.championshipPresentation.formulaOneWhite',
        );

        $repository = new EventStepViewDataRepositoryUsingDoctrine(self::entityManager());

        $view = $repository->find(new EventStepId(self::fixtureId($eventStepFixture)));

        self::assertNotNull($view);

        self::assertSame('Dutch GP', $view->name());
        self::assertSame('formula1', $view->championshipSlug());
        self::assertSame('#fff', $view->color());
        self::assertSame('f1.png', $view->icon());
        self::assertSame('race', $view->type());
        self::assertSame('Circuit Zandvoort', $view->venueLabel());
        self::assertSame('2022-09-04 13:00:00', $view->dateTime()->format('Y-m-d H:i:s'));
    }
}
