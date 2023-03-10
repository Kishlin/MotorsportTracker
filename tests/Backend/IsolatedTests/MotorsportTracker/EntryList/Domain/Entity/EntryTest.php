<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\EntryList\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\EntryList\Domain\DomainEvent\EntryCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\EntryList\Domain\Entity\Entry;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\EntryList\Domain\Entity\Entry
 */
final class EntryTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id         = '8fb7e597-11cf-4ea1-ad0b-17e0cb70d11f';
        $event      = '0b59edc4-d32d-4837-92f7-0cce172efd84';
        $driver     = '1ed156cf-2a34-4032-bea4-fce8fa59f6f5';
        $team       = '1e4e7da6-7f46-4b0f-9830-11d6088cf948';
        $chassis    = 'chassis';
        $engine     = 'engine';
        $seriesName = 'name';
        $seriesSlug = 'slug';
        $carNumber  = '1';

        $entity = Entry::create(
            new UuidValueObject($id),
            new UuidValueObject($event),
            new UuidValueObject($driver),
            new NullableUuidValueObject($team),
            new StringValueObject($chassis),
            new StringValueObject($engine),
            new NullableStringValueObject($seriesName),
            new NullableStringValueObject($seriesSlug),
            new StringValueObject($carNumber),
        );

        self::assertItRecordedDomainEvents($entity, new EntryCreatedDomainEvent(new UuidValueObject($id)));

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($event, $entity->event());
        self::assertValueObjectSame($driver, $entity->driver());
        self::assertValueObjectSame($team, $entity->team());
        self::assertValueObjectSame($chassis, $entity->chassis());
        self::assertValueObjectSame($engine, $entity->engine());
        self::assertValueObjectSame($seriesName, $entity->seriesName());
        self::assertValueObjectSame($seriesSlug, $entity->seriesSlug());
        self::assertValueObjectSame($carNumber, $entity->carNumber());
    }

    public function testItCanBeCreatedWithNullValues(): void
    {
        $id        = '8fb7e597-11cf-4ea1-ad0b-17e0cb70d11f';
        $event     = '0b59edc4-d32d-4837-92f7-0cce172efd84';
        $driver    = '1ed156cf-2a34-4032-bea4-fce8fa59f6f5';
        $chassis   = 'chassis';
        $engine    = 'engine';
        $carNumber = '1';

        $entity = Entry::create(
            new UuidValueObject($id),
            new UuidValueObject($event),
            new UuidValueObject($driver),
            new NullableUuidValueObject(null),
            new StringValueObject($chassis),
            new StringValueObject($engine),
            new NullableStringValueObject(null),
            new NullableStringValueObject(null),
            new StringValueObject($carNumber),
        );

        self::assertItRecordedDomainEvents($entity, new EntryCreatedDomainEvent(new UuidValueObject($id)));

        self::assertNull($entity->team()->value());
        self::assertNull($entity->seriesName()->value());
        self::assertNull($entity->seriesSlug()->value());
    }
}
