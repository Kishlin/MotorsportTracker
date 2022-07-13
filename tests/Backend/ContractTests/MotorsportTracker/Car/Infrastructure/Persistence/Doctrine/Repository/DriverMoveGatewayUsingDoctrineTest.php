<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Car\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Car\Infrastructure\Persistence\Doctrine\Repository\DriverMoveGatewayUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Provider\Country\CountryProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Car\CarProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Car\DriverMoveProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Championship\ChampionshipProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Championship\SeasonProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Driver\DriverProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Team\TeamProvider;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Car\Infrastructure\Persistence\Doctrine\Repository\DriverMoveGatewayUsingDoctrine
 */
final class DriverMoveGatewayUsingDoctrineTest extends RepositoryContractTestCase
{
    public function testItCanSaveACar(): void
    {
        self::loadFixtures(
            CarProvider::redBullRacing2022FirstCar(),
            SeasonProvider::formulaOne2022(),
            TeamProvider::redBullRacing(),
            DriverProvider::dutchDriver(),
            CountryProvider::austria(),
            CountryProvider::netherlands(),
            ChampionshipProvider::formulaOne(),
        );

        $driverMove = DriverMoveProvider::verstappenAtRedBullRacingIn2022();

        $repository = new DriverMoveGatewayUsingDoctrine(self::entityManager());

        $repository->save($driverMove);

        self::assertAggregateRootWasSaved($driverMove);
    }
}
