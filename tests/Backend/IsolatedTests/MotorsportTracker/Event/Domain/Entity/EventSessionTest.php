<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Event\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Event\Domain\DomainEvent\EventSessionCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\EventSession;
use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableDateTimeValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\EventSession
 */
final class EventSessionTest extends AggregateRootIsolatedTestCase
{
    /** @noinspection PhpConditionAlreadyCheckedInspection */
    public function testItCanBeCreated(): void
    {
        $id        = '57140f2c-12ab-433c-a81b-3608c8dfc7c7';
        $typeId    = 'e91b6d7c-6b22-429c-8eb9-3700a711e774';
        $eventId   = 'dc2d323f-9129-48b2-828f-7254e013a9f9';
        $slug      = 'session';
        $hasResult = false;
        $startDate = new \DateTimeImmutable();
        $endDate   = new \DateTimeImmutable();

        $entity = EventSession::create(
            new UuidValueObject($id),
            new UuidValueObject($eventId),
            new UuidValueObject($typeId),
            new StringValueObject($slug),
            new BoolValueObject($hasResult),
            new NullableDateTimeValueObject($startDate),
            new NullableDateTimeValueObject($endDate),
        );

        self::assertItRecordedDomainEvents($entity, new EventSessionCreatedDomainEvent(new UuidValueObject($id)));

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($eventId, $entity->eventId());
        self::assertValueObjectSame($typeId, $entity->typeId());
        self::assertValueObjectSame($slug, $entity->slug());
        self::assertValueObjectSame($hasResult, $entity->hasResult());
        self::assertValueObjectSame($startDate, $entity->startDate());
        self::assertValueObjectSame($endDate, $entity->endDate());
    }
}
