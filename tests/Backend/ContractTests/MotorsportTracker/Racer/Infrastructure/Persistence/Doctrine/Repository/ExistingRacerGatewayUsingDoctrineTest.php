<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\DriverMoveId;
use Kishlin\Backend\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\Repository\ExistingRacerGatewayUsingDoctrine;
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
        self::loadFixtures(
            'motorsport.car.driverMove.verstappenAtRedBullRacingIn2022',
            'motorsport.racer.racer.verstappenToRedBullRacingIn2022',
        );

        $repository = new ExistingRacerGatewayUsingDoctrine(self::entityManager());

        $actual = $repository->findIfExistsForDriverMove(
            new DriverMoveId(self::fixtureId('motorsport.car.driverMove.verstappenAtRedBullRacingIn2022')),
        );

        self::assertNotNull($actual);

        self::assertSame(
            self::fixtureId('motorsport.racer.racer.verstappenToRedBullRacingIn2022'),
            $actual->id()->value(),
        );
    }
}
