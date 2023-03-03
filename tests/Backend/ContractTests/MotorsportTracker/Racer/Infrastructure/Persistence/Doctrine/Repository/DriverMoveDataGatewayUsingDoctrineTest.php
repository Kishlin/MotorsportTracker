<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\DriverMoveId;
use Kishlin\Backend\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\Repository\DriverMoveDataGatewayUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\Repository\DriverMoveDataGatewayUsingDoctrine
 */
final class DriverMoveDataGatewayUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    /**
     * @throws Exception
     */
    public function testItFindsDriverMoveData(): void
    {
        self::loadFixture('motorsport.car.driverMove.verstappenToRedBullRacingIn2022');

        $repository = new DriverMoveDataGatewayUsingDoctrine(self::entityManager());

        $data = $repository->find(
            new DriverMoveId(
                self::fixtureId('motorsport.car.driverMove.verstappenToRedBullRacingIn2022'),
            ),
        );

        self::assertSame(self::fixtureId('motorsport.car.car.redBullRacing2022FirstCar'), $data->carId());
        self::assertSame(self::fixtureId('motorsport.driver.driver.maxVerstappen'), $data->driverId());
        self::assertSame(2022, (int) $data->date()->format('Y'));
    }
}
