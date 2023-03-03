<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\Event;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventIndex;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventLabel;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventSeasonId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventVenueId;
use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\EventRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\EventRepositoryUsingDoctrine
 */
final class EventRepositoryUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    public function testItCanSaveAnEvent(): void
    {
        self::loadFixtures(
            'motorsport.venue.venue.zandvoort',
            'motorsport.championship.season.formulaOne2022',
        );

        $event = Event::instance(
            new EventId(self::uuid()),
            new EventSeasonId(self::fixtureId('motorsport.championship.season.formulaOne2022')),
            new EventVenueId(self::fixtureId('motorsport.venue.venue.zandvoort')),
            new EventIndex(14),
            new EventLabel('Dutch GP'),
        );

        $repository = new EventRepositoryUsingDoctrine(self::entityManager());

        $repository->save($event);

        self::assertAggregateRootWasSaved($event);
    }
}
