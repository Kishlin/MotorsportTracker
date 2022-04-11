<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Venue\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Venue\Domain\DomainEvent\VenueCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\Entity\Venue;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\ValueObject\VenueCountryId;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\ValueObject\VenueId;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\ValueObject\VenueName;
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

        $entity = Venue::create(new VenueId($id), new VenueName($name), new VenueCountryId($countryId));

        self::assertItRecordedDomainEvents($entity, new VenueCreatedDomainEvent(new VenueId($id)));

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($name, $entity->name());
        self::assertValueObjectSame($countryId, $entity->countryId());
    }
}
