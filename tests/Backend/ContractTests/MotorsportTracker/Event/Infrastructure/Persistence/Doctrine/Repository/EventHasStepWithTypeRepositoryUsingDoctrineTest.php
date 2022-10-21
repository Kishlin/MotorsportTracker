<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepEventId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepTypeId;
use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\EventHasStepWithTypeRepositoryUsingDoctrine;
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
 * @covers \Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\EventHasStepWithTypeRepositoryUsingDoctrine
 */
final class EventHasStepWithTypeRepositoryUsingDoctrineTest extends RepositoryContractTestCase
{
    /**
     * @throws NonUniqueResultException
     */
    public function testItReturnsFalseIfNoEventStepExists(): void
    {
        $repository = new EventHasStepWithTypeRepositoryUsingDoctrine($this->entityManager());

        self::assertFalse($repository->eventHasStepWithType(
            new EventStepEventId('84b3e2e0-0f81-4747-be83-bcbf958b7105'),
            new EventStepTypeId('461231da-0c8c-43e9-adbf-dadca3e0d65d'),
        ));
    }

    /**
     * @throws NonUniqueResultException
     */
    public function testItReturnsTrueIfAnEventStepExistsWithTheType(): void
    {
        self::loadFixtures(
            EventStepProvider::dutchGrandPrixRace(),
            EventProvider::dutchGrandPrix(),
            StepTypeProvider::race(),
            VenueProvider::dutchVenue(),
            CountryProvider::netherlands(),
            SeasonProvider::formulaOne2022(),
            ChampionshipProvider::formulaOne(),
        );

        $repository = new EventHasStepWithTypeRepositoryUsingDoctrine($this->entityManager());

        self::assertTrue($repository->eventHasStepWithType(
            new EventStepEventId('84b3e2e0-0f81-4747-be83-bcbf958b7105'),
            new EventStepTypeId('461231da-0c8c-43e9-adbf-dadca3e0d65d'),
        ));
    }
}
