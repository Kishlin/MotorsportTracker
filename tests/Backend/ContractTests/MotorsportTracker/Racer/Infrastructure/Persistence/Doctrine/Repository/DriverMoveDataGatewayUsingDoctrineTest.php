<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\Repository\DriverMoveDataGatewayUsingDoctrine;
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
 * @covers \Kishlin\Backend\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\Repository\DriverMoveDataGatewayUsingDoctrine
 */
final class DriverMoveDataGatewayUsingDoctrineTest extends RepositoryContractTestCase
{
    /**
     * @throws Exception
     */
    public function testItFindsDriverMoveData(): void
    {
        $driverMove = DriverMoveProvider::verstappenAtRedBullRacingIn2022();

        self::loadFixtures(
            $driverMove,
            CarProvider::redBullRacing2022FirstCar(),
            TeamProvider::redBullRacing(),
            DriverProvider::dutchDriver(),
            CountryProvider::austria(),
            CountryProvider::netherlands(),
            SeasonProvider::formulaOne2022(),
            ChampionshipProvider::formulaOne(),
        );

        $repository = new DriverMoveDataGatewayUsingDoctrine(self::entityManager());

        $data = $repository->find($driverMove->id());

        self::assertSame($data->carId(), $driverMove->carId()->value());
        self::assertSame($data->driverId(), $driverMove->driverId()->value());
        self::assertSame($data->date()->format(DATE_ATOM), $driverMove->date()->value()->format(DATE_ATOM));
    }
}
