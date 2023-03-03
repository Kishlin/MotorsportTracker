<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Car\Infrastructure\Persistence\Doctrine\Repository;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportTracker\Car\Domain\Entity\DriverMove;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\DriverMoveCarId;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\DriverMoveDate;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\DriverMoveDriverId;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\DriverMoveId;
use Kishlin\Backend\MotorsportTracker\Car\Infrastructure\Persistence\Doctrine\Repository\DriverMoveGatewayUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Car\Infrastructure\Persistence\Doctrine\Repository\DriverMoveGatewayUsingDoctrine
 */
final class DriverMoveGatewayUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    public function testItCanSaveADriverMove(): void
    {
        self::loadFixtures(
            'motorsport.driver.driver.maxVerstappen',
            'motorsport.car.car.redBullRacing2022FirstCar',
        );

        $driverMove = DriverMove::instance(
            new DriverMoveId(self::uuid()),
            new DriverMoveDriverId(self::fixtureId('motorsport.driver.driver.maxVerstappen')),
            new DriverMoveCarId(self::fixtureId('motorsport.car.car.redBullRacing2022FirstCar')),
            new DriverMoveDate(new DateTimeImmutable('2022-01-01')),
        );

        $repository = new DriverMoveGatewayUsingDoctrine(self::entityManager());

        $repository->save($driverMove);

        self::assertAggregateRootWasSaved($driverMove);
    }
}
