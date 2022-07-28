<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\Repository\RacerGatewayUsingDoctrine;
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
 * @covers \Kishlin\Backend\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\Repository\RacerGatewayUsingDoctrine
 */
final class RacerGatewayUsingDoctrineTest extends RepositoryContractTestCase
{
    public function testItCanSaveADriver(): void
    {
        self::loadFixtures(
            CarProvider::redBullRacing2022FirstCar(),
            TeamProvider::redBullRacing(),
            DriverProvider::dutchDriver(),
            CountryProvider::austria(),
            CountryProvider::netherlands(),
            SeasonProvider::formulaOne2022(),
            ChampionshipProvider::formulaOne(),
        );

        $racer = RacerProvider::verstappenToRedBullRacingIn2022();

        $repository = new RacerGatewayUsingDoctrine(self::entityManager());

        $repository->save($racer);

        self::assertAggregateRootWasSaved($racer);
    }
}
