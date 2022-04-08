<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Event\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Event\Domain\DomainEvent\EventStepCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\EventStep;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepDateTime;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepEventId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepTypeId;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

final class EventStepTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id       = '57140f2c-12ab-433c-a81b-3608c8dfc7c7';
        $typeId   = 'e91b6d7c-6b22-429c-8eb9-3700a711e774';
        $eventId  = 'dc2d323f-9129-48b2-828f-7254e013a9f9';
        $dateTime = new \DateTimeImmutable();

        $entity = EventStep::create(
            new EventStepId($id),
            new EventStepTypeId($typeId),
            new EventStepEventId($eventId),
            new EventStepDateTime($dateTime),
        );

        self::assertItRecordedDomainEvents($entity, new EventStepCreatedDomainEvent(new EventStepId($id)));

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($typeId, $entity->typeId());
        self::assertValueObjectSame($eventId, $entity->eventId());
        self::assertValueObjectSame($dateTime, $entity->dateTime());
    }
}
