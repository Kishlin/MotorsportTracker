<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\DriverStanding;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingDriverId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingEventId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingPoints;
use Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\Repository\DriverStandingRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\Repository\DriverStandingRepositoryUsingDoctrine
 */
final class DriverStandingRepositoryUsingDoctrineTest extends CoreRepositoryContractTestCase
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

    public function testItDoesNotFindAMissingStanding(): void
    {
        $repository = new DriverStandingRepositoryUsingDoctrine(self::entityManager());

        self::assertNull($repository->find(
            new DriverStandingDriverId('07595707-3f51-4f39-89c7-cf2aa4c01c37'),
            new DriverStandingEventId('0da0c482-6c2a-4c30-87cc-8755914ff300'),
        ));
    }

    public function testItFindsAnExistingStanding(): void
    {
        self::loadFixture('motorsport.standing.driverStanding.verstappenAfterAustralianGP2022');

        $repository = new DriverStandingRepositoryUsingDoctrine(self::entityManager());

        $standing = $repository->find(
            new DriverStandingDriverId(self::fixtureId('motorsport.driver.driver.maxVerstappen')),
            new DriverStandingEventId(self::fixtureId('motorsport.event.event.australianGrandPrix2022')),
        );

        self::assertInstanceOf(DriverStanding::class, $standing);
        self::assertSame(0.0, $standing->points()->value());
    }
}
