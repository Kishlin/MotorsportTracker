<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Entry\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Result\Domain\DomainEvent\RaceLapCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\RaceLap;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\TyreDetailsValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @coversNothing
 */
final class RaceLapTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id          = '5934247f-a575-4875-b73d-a3ec2c4220dc';
        $entry       = 'ef7b1df5-1d84-47b8-bb35-b18554a56976';
        $lap         = 1;
        $position    = 9;
        $time        = 99745;
        $timeToLead  = 10957;
        $lapsToLead  = 0;
        $timeToNext  = 602;
        $lapsToNext  = 0;
        $tyreDetails = [['type' => 'S', 'wear' => 'u', 'laps' => 6]];

        $entity = RaceLap::create(
            new UuidValueObject($id),
            new UuidValueObject($entry),
            new StrictlyPositiveIntValueObject($lap),
            new StrictlyPositiveIntValueObject($position),
            new BoolValueObject(false),
            new StrictlyPositiveIntValueObject($time),
            new StrictlyPositiveIntValueObject($timeToLead),
            new PositiveIntValueObject($lapsToLead),
            new StrictlyPositiveIntValueObject($timeToNext),
            new PositiveIntValueObject($lapsToNext),
            new TyreDetailsValueObject($tyreDetails),
        );

        self::assertItRecordedDomainEvents($entity, new RaceLapCreatedDomainEvent(new UuidValueObject($id)));

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($entry, $entity->entry());
        self::assertValueObjectSame($lap, $entity->lap());
        self::assertValueObjectSame($position, $entity->position());
        self::assertValueObjectSame($position, $entity->position());
        self::assertValueObjectSame(false, $entity->pit());
        self::assertValueObjectSame($timeToLead, $entity->timeToLead());
        self::assertValueObjectSame($lapsToLead, $entity->lapsToLead());
        self::assertValueObjectSame($timeToNext, $entity->timeToNext());
        self::assertValueObjectSame($lapsToNext, $entity->lapsToNext());
        self::assertValueObjectSame($tyreDetails, $entity->tyreDetails());
    }
}
