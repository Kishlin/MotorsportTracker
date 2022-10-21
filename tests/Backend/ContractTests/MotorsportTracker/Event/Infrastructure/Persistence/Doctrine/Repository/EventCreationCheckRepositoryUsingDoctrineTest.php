<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventIndex;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventLabel;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventSeasonId;
use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\EventCreationCheckRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Provider\Country\CountryProvider;
use Kishlin\Tests\Backend\Tools\Provider\Event\EventProvider;
use Kishlin\Tests\Backend\Tools\Provider\Event\StepTypeProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Championship\ChampionshipProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Championship\SeasonProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Venue\VenueProvider;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\EventCreationCheckRepositoryUsingDoctrine
 */
final class EventCreationCheckRepositoryUsingDoctrineTest extends RepositoryContractTestCase
{
    /**
     * @throws NonUniqueResultException
     */
    public function testItReturnsTrueIfAnEventExistsWithTheSameLabel(): void
    {
        self::loadFixtures(
            EventProvider::dutchGrandPrix(),
            StepTypeProvider::race(),
            VenueProvider::dutchVenue(),
            CountryProvider::netherlands(),
            SeasonProvider::formulaOne2022(),
            ChampionshipProvider::formulaOne(),
        );

        $repository = new EventCreationCheckRepositoryUsingDoctrine(self::entityManager());

        self::assertTrue($repository->seasonHasEventWithIndexOrVenue(
            new EventSeasonId('01dd2498-e231-4f34-82de-bf61153abbc4'),
            new EventIndex(0),
            new EventLabel('Dutch GP'),
        ));
    }

    /**
     * @throws NonUniqueResultException
     */
    public function testItReturnsTrueIfAnEventExistsWithTheSameIndex(): void
    {
        self::loadFixtures(
            EventProvider::dutchGrandPrix(),
            StepTypeProvider::race(),
            VenueProvider::dutchVenue(),
            CountryProvider::netherlands(),
            SeasonProvider::formulaOne2022(),
            ChampionshipProvider::formulaOne(),
        );

        $repository = new EventCreationCheckRepositoryUsingDoctrine(self::entityManager());

        self::assertTrue($repository->seasonHasEventWithIndexOrVenue(
            new EventSeasonId('01dd2498-e231-4f34-82de-bf61153abbc4'),
            new EventIndex(16),
            new EventLabel('Free Label'),
        ));
    }

    /**
     * @throws NonUniqueResultException
     */
    public function testItReturnsFalseIfTheIndexAndLabelAreFreeToUse(): void
    {
        $repository = new EventCreationCheckRepositoryUsingDoctrine(self::entityManager());

        self::assertFalse($repository->seasonHasEventWithIndexOrVenue(
            new EventSeasonId('01dd2498-e231-4f34-82de-bf61153abbc4'),
            new EventIndex(0),
            new EventLabel('Free Label'),
        ));
    }
}
