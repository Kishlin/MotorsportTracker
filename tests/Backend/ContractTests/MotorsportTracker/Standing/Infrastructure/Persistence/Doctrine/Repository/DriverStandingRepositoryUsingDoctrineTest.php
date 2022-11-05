<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\DriverStanding;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingDriverId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingEventId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingPoints;
use Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\Repository\DriverStandingRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\Repository\DriverStandingRepositoryUsingDoctrine
 */
final class DriverStandingRepositoryUsingDoctrineTest extends RepositoryContractTestCase
{
    public function testItCanSaveAnEntity(): void
    {
        self::loadFixtures(
            'motorsport.event.event.dutchGrandPrix2022',
            'motorsport.driver.driver.maxVerstappen',
        );

        $teamStanding = DriverStanding::instance(
            new DriverStandingId(self::uuid()),
            new DriverStandingEventId(self::fixtureId('motorsport.event.event.dutchGrandPrix2022')),
            new DriverStandingDriverId(self::fixtureId('motorsport.driver.driver.maxVerstappen')),
            new DriverStandingPoints(12.0),
        );

        $repository = new DriverStandingRepositoryUsingDoctrine(self::entityManager());

        $repository->save($teamStanding);

        self::assertAggregateRootWasSaved($teamStanding);
    }
}
