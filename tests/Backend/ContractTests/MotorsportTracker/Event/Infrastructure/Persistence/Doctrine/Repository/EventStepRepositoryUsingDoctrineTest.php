<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\EventStepRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Provider\Country\CountryProvider;
use Kishlin\Tests\Backend\Tools\Provider\Event\EventProvider;
use Kishlin\Tests\Backend\Tools\Provider\Event\EventStepProvider;
use Kishlin\Tests\Backend\Tools\Provider\Event\StepTypeProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Championship\ChampionshipProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Championship\SeasonProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Venue\VenueProvider;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\EventStepRepositoryUsingDoctrine
 */
final class EventStepRepositoryUsingDoctrineTest extends RepositoryContractTestCase
{
    public function testItCanSaveAnEventStep(): void
    {
        self::loadFixtures(
            EventProvider::dutchGrandPrix(),
            StepTypeProvider::race(),
            VenueProvider::dutchVenue(),
            CountryProvider::netherlands(),
            SeasonProvider::formulaOne2022(),
            ChampionshipProvider::formulaOne(),
        );

        $eventStep = EventStepProvider::dutchGrandPrixRace();

        $repository = new EventStepRepositoryUsingDoctrine(self::entityManager());

        $repository->save($eventStep);

        self::assertAggregateRootWasSaved($eventStep);
    }
}
