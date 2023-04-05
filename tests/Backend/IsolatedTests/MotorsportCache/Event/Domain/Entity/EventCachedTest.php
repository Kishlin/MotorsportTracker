<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportCache\Event\Domain\Entity;

use Kishlin\Backend\MotorsportCache\Event\Domain\DomainEvent\EventCachedCreatedDomainEvent;
use Kishlin\Backend\MotorsportCache\Event\Domain\Entity\EventCached;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportCache\Event\Domain\Entity\EventCached
 */
final class EventCachedTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id           = '108a247a-96c2-4e59-b7d6-03642ad143c5';
        $championship = 'formula-one';
        $year         = 2022;
        $eventSlug    = 'bahrain-grand-prix';

        $entity = EventCached::create(
            new UuidValueObject($id),
            new StringValueObject($championship),
            new StrictlyPositiveIntValueObject($year),
            new StringValueObject($eventSlug),
        );

        self::assertItRecordedDomainEvents($entity, new EventCachedCreatedDomainEvent(new UuidValueObject($id)));

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($championship, $entity->championship());
        self::assertValueObjectSame($year, $entity->year());
        self::assertValueObjectSame($eventSlug, $entity->eventSlug());
    }
}
