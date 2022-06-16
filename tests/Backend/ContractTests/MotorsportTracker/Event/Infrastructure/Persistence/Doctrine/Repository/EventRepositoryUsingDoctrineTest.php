<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\EventRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Provider\Country\CountryProvider;
use Kishlin\Tests\Backend\Tools\Provider\Event\EventProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Championship\ChampionshipProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Championship\SeasonProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Venue\VenueProvider;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\EventRepositoryUsingDoctrine
 */
final class EventRepositoryUsingDoctrineTest extends RepositoryContractTestCase
{
    public function testItCanSaveAnEvent(): void
    {
        self::loadFixtures(
            VenueProvider::dutchVenue(),
            SeasonProvider::formulaOne2022(),
            ChampionshipProvider::formulaOne(),
            CountryProvider::netherlands(),
        );

        $event = EventProvider::dutchGrandPrix();

        $repository = new EventRepositoryUsingDoctrine(self::entityManager());

        $repository->save($event);

        self::assertAggregateRootWasSaved($event);
    }
}
