<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Car\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Car\Infrastructure\Persistence\Doctrine\Repository\CarGatewayUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Provider\Country\CountryProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Car\CarProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Championship\ChampionshipProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Championship\SeasonProvider;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Team\TeamProvider;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Car\Infrastructure\Persistence\Doctrine\Repository\CarGatewayUsingDoctrine
 */
final class CarGatewayUsingDoctrineTest extends RepositoryContractTestCase
{
    public function testItCanSaveACar(): void
    {
        self::loadFixtures(
            SeasonProvider::formulaOne2022(),
            TeamProvider::redBullRacing(),
            CountryProvider::austria(),
            ChampionshipProvider::formulaOne(),
        );

        $car = CarProvider::redBullRacing2022FirstCar();

        $repository = new CarGatewayUsingDoctrine(self::entityManager());

        $repository->save($car);

        self::assertAggregateRootWasSaved($car);
    }
}
