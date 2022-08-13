<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Racer\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Racer\Domain\DomainEvent\RacerCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\Entity\Racer;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerCarId;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerDriverId;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerEndDate;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerId;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerStartDate;
use Kishlin\Tests\Backend\Tools\Provider\MotorsportTracker\Racer\RacerProvider;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Racer\Domain\Entity\Racer
 */
final class RacerTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id        = '99164eb7-5a6e-434e-87c5-92be8a608018';
        $carId     = '6cf4177d-89ed-470d-ae98-50014f2ee0d6';
        $driverId  = '74c6f7a3-0409-4a3d-9af5-1956d4b08e40';
        $startDate = new \DateTimeImmutable('1993-11-22');
        $endDate   = new \DateTimeImmutable('1993-11-25');

        $entity = Racer::create(
            new RacerId($id),
            new RacerDriverId($driverId),
            new RacerCarId($carId),
            new RacerStartDate($startDate),
            new RacerEndDate($endDate),
        );

        self::assertItRecordedDomainEvents($entity, new RacerCreatedDomainEvent(new RacerId($id)));

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($carId, $entity->carId());
        self::assertValueObjectSame($driverId, $entity->driverId());
        self::assertValueObjectSame($startDate, $entity->startDate());
        self::assertValueObjectSame($endDate, $entity->endDate());
    }

    public function testItCanHaveANewEndDate(): void
    {
        $entity = RacerProvider::verstappenToRedBullRacingIn2022();

        $entity->nowEndsJustBefore(new \DateTimeImmutable('2022-07-01 00:00:00'));

        self::assertSame('2022-06-30 23:59:59', $entity->endDate()->value()->format('Y-m-d H:i:s'));
    }
}
