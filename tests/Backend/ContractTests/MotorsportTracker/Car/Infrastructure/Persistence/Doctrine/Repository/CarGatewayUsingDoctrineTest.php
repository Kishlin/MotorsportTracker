<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Car\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Car\Domain\Entity\Car;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarId;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarNumber;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarSeasonId;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarTeamId;
use Kishlin\Backend\MotorsportTracker\Car\Infrastructure\Persistence\Doctrine\Repository\CarGatewayUsingDoctrine;
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
            'motorsport.team.team.redBullRacing',
            'motorsport.championship.season.formulaOne2022',
        );

        $car = Car::instance(
            new CarId(self::uuid()),
            new CarTeamId(self::fixtureId('motorsport.team.team.redBullRacing')),
            new CarSeasonId(self::fixtureId('motorsport.championship.season.formulaOne2022')),
            new CarNumber(1),
        );

        $repository = new CarGatewayUsingDoctrine(self::entityManager());

        $repository->save($car);

        self::assertAggregateRootWasSaved($car);
    }
}
