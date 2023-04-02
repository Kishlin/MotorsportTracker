<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportCache\Event\Domain\Entity;

use Kishlin\Backend\MotorsportCache\Event\Domain\DomainEvent\SeasonEventsCreatedDomainEvent;
use Kishlin\Backend\MotorsportCache\Event\Domain\Entity\SeasonEvents;
use Kishlin\Backend\MotorsportCache\Event\Domain\ValueObject\SeasonEventList;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportCache\Event\Domain\Entity\SeasonEvents
 */
final class SeasonEventsTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id           = '108a247a-96c2-4e59-b7d6-03642ad143c5';
        $championship = 'Formula One';
        $year         = 2022;
        $events       = [
            'bahrain-grand-prix' => [
                'id'    => '85f72012-ab0f-4794-bd95-a3b35bfde3b7',
                'name'  => 'Bahrain Grand Prix',
                'slug'  => 'bahrain-grand-prix',
                'index' => 0,
            ],
            'saudi-arabian-grand-prix' => [
                'id'    => '57381c8f-bb99-4315-9483-32f1d325cdd7',
                'name'  => 'Saudi Arabian Grand Prix',
                'slug'  => 'saudi-arabian-grand-prix',
                'index' => 1,
            ],
            'australian-grand-prix' => [
                'id'    => 'aa8d6db8-f460-43e5-86ee-167d8681cae6',
                'name'  => 'Australian Grand Prix',
                'slug'  => 'australian-grand-prix',
                'index' => 2,
            ],
        ];

        $seasonEvents = SeasonEvents::create(
            new UuidValueObject($id),
            new StringValueObject($championship),
            new StrictlyPositiveIntValueObject($year),
            SeasonEventList::fromData($events),
        );

        self::assertItRecordedDomainEvents($seasonEvents, new SeasonEventsCreatedDomainEvent(new UuidValueObject($id)));

        self::assertValueObjectSame($id, $seasonEvents->id());
        self::assertValueObjectSame($championship, $seasonEvents->championship());
        self::assertValueObjectSame($year, $seasonEvents->year());
        self::assertValueObjectSame($events, $seasonEvents->events());
    }
}
