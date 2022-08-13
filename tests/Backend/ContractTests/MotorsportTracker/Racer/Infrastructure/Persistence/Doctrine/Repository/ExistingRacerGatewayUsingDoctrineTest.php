<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\DriverMoveId;
use Kishlin\Backend\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\Repository\ExistingRacerGatewayUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Provider\Country\CountryProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Car\CarProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Car\DriverMoveProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Championship\ChampionshipProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Championship\SeasonProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Driver\DriverProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Racer\RacerProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Team\TeamProvider;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\Repository\ExistingRacerGatewayUsingDoctrine
 */
final class ExistingRacerGatewayUsingDoctrineTest extends RepositoryContractTestCase
{
    /**
     * @throws NonUniqueResultException
     */
    public function testItReturnsNullWhenThereIsNoMatchingRacer(): void
    {
        $driverMoveId = new DriverMoveId('46998b61-b0be-4b5b-b17e-52a2888ba99e');

        $repository = new ExistingRacerGatewayUsingDoctrine(self::entityManager());

        self::assertNull($repository->findIfExistsForDriverMove($driverMoveId));
    }

    /**
     * @throws NonUniqueResultException
     */
    public function testItCanFindAnExistingRacerId(): void
    {
        $driverMove = DriverMoveProvider::verstappenAtRedBullRacingIn2022();
        $expected   = RacerProvider::verstappenToRedBullRacingIn2022();

        self::loadFixtures(
            $expected,
            $driverMove,
            CarProvider::redBullRacing2022FirstCar(),
            TeamProvider::redBullRacing(),
            DriverProvider::dutchDriver(),
            CountryProvider::austria(),
            CountryProvider::netherlands(),
            SeasonProvider::formulaOne2022(),
            ChampionshipProvider::formulaOne(),
        );

        $repository = new ExistingRacerGatewayUsingDoctrine(self::entityManager());

        $actual = $repository->findIfExistsForDriverMove($driverMove->id());

        self::assertNotNull($actual);

        self::assertSame($expected->id()->value(), $actual->id()->value());
    }
}
