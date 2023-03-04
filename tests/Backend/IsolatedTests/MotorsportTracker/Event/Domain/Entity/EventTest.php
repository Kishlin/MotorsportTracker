<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Event\Domain\Entity;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\Event;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableDateTimeValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\Event
 */
final class EventTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id        = '45f372af-1fe8-43c9-867b-ad40e1b2fc03';
        $seasonId  = 'bac9b06e-ddfb-45a7-b9b3-49214a9c48fc';
        $venueId   = '6c6f11a5-4360-4cd4-8f07-3ee90f3ed8bd';
        $index     = 0;
        $slug      = 'dutch-gp';
        $name      = 'Dutch Grand Prix';
        $shortName = 'Dutch GP';
        $startDate = new DateTimeImmutable('2022-11-22 01:00:00');
        $endDate   = new DateTimeImmutable('2022-11-22 02:00:00');

        $entity = Event::instance(
            new UuidValueObject($id),
            new UuidValueObject($seasonId),
            new UuidValueObject($venueId),
            new PositiveIntValueObject($index),
            new StringValueObject($slug),
            new StringValueObject($name),
            new NullableStringValueObject($shortName),
            new NullableDateTimeValueObject($startDate),
            new NullableDateTimeValueObject($endDate),
        );

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($seasonId, $entity->seasonId());
        self::assertValueObjectSame($venueId, $entity->venueId());
        self::assertValueObjectSame($index, $entity->index());
        self::assertValueObjectSame($slug, $entity->slug());
        self::assertValueObjectSame($name, $entity->name());
        self::assertValueObjectSame($shortName, $entity->shortName());
        self::assertValueObjectSame($startDate, $entity->startDate());
        self::assertValueObjectSame($endDate, $entity->endDate());
    }

    public function testItCanBeCreatedWithNullValues(): void
    {
        $id       = '45f372af-1fe8-43c9-867b-ad40e1b2fc03';
        $seasonId = 'bac9b06e-ddfb-45a7-b9b3-49214a9c48fc';
        $venueId  = '6c6f11a5-4360-4cd4-8f07-3ee90f3ed8bd';
        $index    = 0;
        $slug     = 'dutch-gp';
        $name     = 'Dutch Grand Prix';

        $entity = Event::instance(
            new UuidValueObject($id),
            new UuidValueObject($seasonId),
            new UuidValueObject($venueId),
            new PositiveIntValueObject($index),
            new StringValueObject($slug),
            new StringValueObject($name),
            new NullableStringValueObject(null),
            new NullableDateTimeValueObject(null),
            new NullableDateTimeValueObject(null),
        );

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($seasonId, $entity->seasonId());
        self::assertValueObjectSame($venueId, $entity->venueId());
        self::assertValueObjectSame($index, $entity->index());
        self::assertValueObjectSame($slug, $entity->slug());
        self::assertValueObjectSame($name, $entity->name());
        self::assertValueObjectSame(null, $entity->shortName());
        self::assertValueObjectSame(null, $entity->startDate());
        self::assertValueObjectSame(null, $entity->endDate());
    }
}
