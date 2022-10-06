<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\Repository;

use DateTimeImmutable;
use Exception;
use Kishlin\Backend\MotorsportTracker\Racer\Application\GetAllRacersForDateTime\SeasonId;
use Kishlin\Backend\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\Repository\RacersForDateTimeAndSeasonGatewayUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Provider\Country\CountryProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Car\CarProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Championship\ChampionshipProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Championship\SeasonProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Driver\DriverProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Racer\RacerProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Team\TeamProvider;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\Repository\RacersForDateTimeAndSeasonGatewayUsingDoctrine
 */
final class RacersForDateTimeGatewayUsingDoctrineContractTest extends RepositoryContractTestCase
{
    /**
     * @throws Exception
     */
    public function testItCanRetrieveARacerForADate(): void
    {
        $racerOne = RacerProvider::verstappenToRedBullRacingIn2022();
        $racerTwo = RacerProvider::perezToRedBulRacingIn2022();

        $season = SeasonProvider::formulaOne2022();
        self::loadFixtures(
            $racerOne,
            $racerTwo,
            CarProvider::redBullRacing2022SecondCar(),
            CarProvider::redBullRacing2022FirstCar(),
            DriverProvider::mexicanDriver(),
            TeamProvider::redBullRacing(),
            DriverProvider::dutchDriver(),
            CountryProvider::mexico(),
            CountryProvider::austria(),
            CountryProvider::netherlands(),
            $season,
            ChampionshipProvider::formulaOne(),
        );

        $repository = new RacersForDateTimeAndSeasonGatewayUsingDoctrine(self::entityManager());

        $racers = $repository->findRacersForDateTimeAndSeason(
            new DateTimeImmutable(sprintf('%d-06-01', $season->year()->value())),
            SeasonId::fromOther($season->id()),
        );

        self::assertCount(2, $racers);
        self::assertSame($racerOne->id()->value(), $racers[1]->racerId());
        self::assertSame($racerTwo->id()->value(), $racers[0]->racerId());
    }
}
