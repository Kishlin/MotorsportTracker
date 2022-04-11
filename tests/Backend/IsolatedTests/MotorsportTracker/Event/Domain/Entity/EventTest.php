<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Event\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Event\Domain\DomainEvent\EventCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\Event;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventIndex;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventLabel;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventSeasonId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventVenueId;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\Event
 */
final class EventTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id       = '45f372af-1fe8-43c9-867b-ad40e1b2fc03';
        $seasonId = 'bac9b06e-ddfb-45a7-b9b3-49214a9c48fc';
        $venueId  = '6c6f11a5-4360-4cd4-8f07-3ee90f3ed8bd';
        $label    = 'Venue GP';
        $index    = 0;

        $entity = Event::create(
            new EventId($id),
            new EventSeasonId($seasonId),
            new EventVenueId($venueId),
            new EventIndex($index),
            new EventLabel($label),
        );

        self::assertItRecordedDomainEvents($entity, new EventCreatedDomainEvent(new EventId($id)));

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($seasonId, $entity->seasonId());
        self::assertValueObjectSame($venueId, $entity->venueId());
        self::assertValueObjectSame($index, $entity->index());
        self::assertValueObjectSame($label, $entity->label());
    }
}
