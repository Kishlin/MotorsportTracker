<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Standing\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\DriverStanding;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingDriverId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingEventId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingPoints;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\DriverStanding
 */
final class DriverStandingTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id       = 'fbe061d6-c49a-4c3d-9040-3ade9a3c47ee';
        $eventId  = 'e9bc1436-48c1-4760-9452-e63cc5e17c28';
        $driverId = '50c04573-c83c-49c9-828c-572926fc230b';
        $points   = 15.0;

        $entity = DriverStanding::create(
            new DriverStandingId($id),
            new DriverStandingEventId($eventId),
            new DriverStandingDriverId($driverId),
            new DriverStandingPoints($points),
        );

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($eventId, $entity->eventId());
        self::assertValueObjectSame($driverId, $entity->driverId());
        self::assertValueObjectSame($points, $entity->points());
    }
}
