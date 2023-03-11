<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Venue\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Venue\Domain\DomainEvent\VenueCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\Entity\Venue;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Venue\Domain\Entity\Venue
 */
final class VenueTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id        = '024ceb5a-ae39-44e4-99d9-5713004b8a4b';
        $name      = 'Venue Track';
        $countryId = 'fee5ddb6-d528-48d2-aa7d-901728a84d70';
        $ref       = 'fe88a1ff-7758-49d6-982b-575c6b8cb1cc';

        $entity = Venue::create(
            new UuidValueObject($id),
            new StringValueObject($name),
            new UuidValueObject($countryId),
            new NullableUuidValueObject($ref),
        );

        self::assertItRecordedDomainEvents($entity, new VenueCreatedDomainEvent(new UuidValueObject($id)));

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($name, $entity->name());
        self::assertValueObjectSame($countryId, $entity->countryId());
        self::assertValueObjectSame($ref, $entity->ref());
    }
}
