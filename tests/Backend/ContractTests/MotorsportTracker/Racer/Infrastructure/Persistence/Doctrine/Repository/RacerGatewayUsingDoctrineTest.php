<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\Repository;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\Entity\Racer;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerCarId;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerDriverId;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerEndDate;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerId;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerStartDate;
use Kishlin\Backend\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\Repository\RacerGatewayUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\Repository\RacerGatewayUsingDoctrine
 */
final class RacerGatewayUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    public function testItCanSaveARacer(): void
    {
        self::loadFixtures(
            'motorsport.driver.driver.maxVerstappen',
            'motorsport.car.car.redBullRacing2022FirstCar',
        );

        $racer = Racer::instance(
            new RacerId(self::uuid()),
            new RacerDriverId(self::fixtureId('motorsport.driver.driver.maxVerstappen')),
            new RacerCarId(self::fixtureId('motorsport.car.car.redBullRacing2022FirstCar')),
            new RacerStartDate(new DateTimeImmutable('2022-01-01')),
            new RacerEndDate(new DateTimeImmutable('2022-12-31')),
        );

        $repository = new RacerGatewayUsingDoctrine(self::entityManager());

        $repository->save($racer);

        self::assertAggregateRootWasSaved($racer);
    }
}
