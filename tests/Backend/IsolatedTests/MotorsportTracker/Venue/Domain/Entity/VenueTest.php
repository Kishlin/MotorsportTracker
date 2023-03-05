<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Venue\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Venue\Domain\DomainEvent\VenueCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\Entity\Venue;
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
        $countryId = 'fee5ddb6-d528-48d2-aa7d-901728a84d70';
        $name      = 'Venue Track';
        $slug      = 'venue-track';

        $entity = Venue::create(
            new UuidValueObject($id),
            new StringValueObject($name),
            new StringValueObject($slug),
            new UuidValueObject($countryId),
        );

        self::assertItRecordedDomainEvents($entity, new VenueCreatedDomainEvent(new UuidValueObject($id)));

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($name, $entity->name());
        self::assertValueObjectSame($slug, $entity->slug());
        self::assertValueObjectSame($countryId, $entity->countryId());
    }
}
