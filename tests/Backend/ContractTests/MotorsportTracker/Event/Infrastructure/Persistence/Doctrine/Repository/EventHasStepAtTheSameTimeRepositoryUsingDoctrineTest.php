<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository;

use DateTimeImmutable;
use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepDateTime;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepEventId;
use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\EventHasStepAtTheSameTimeRepositoryUsingDoctrine;
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
            new EventStepEventId('84b3e2e0-0f81-4747-be83-bcbf958b7105'),
            new EventStepDateTime(new DateTimeImmutable('2022-09-04 15:00:00')),
        ));
    }

    /**
     * @throws NonUniqueResultException
     */
    public function testItReturnsTrueIfAnEventStepExistsWithTheSameTime(): void
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

        $repository = new EventHasStepAtTheSameTimeRepositoryUsingDoctrine($this->entityManager());

        self::assertTrue($repository->eventHasStepAtTheSameTime(
            new EventStepEventId('84b3e2e0-0f81-4747-be83-bcbf958b7105'),
            new EventStepDateTime(new DateTimeImmutable('2022-09-04 15:00:00')),
        ));
    }
}
