<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportCache\Calendar\Domain\Entity;

use Exception;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway\CalendarEventEntry;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\Entity\CalendarEvent;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventSeries;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportCache\Calendar\Domain\Entity\CalendarEvent
 * @covers \Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventSeries
 * @covers \Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventSessions
 * @covers \Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventVenue
 */
final class CalendarEventTest extends AggregateRootIsolatedTestCase
{
    /**
     * @throws Exception
     */
    public function testItCanBeCreated(): void
    {
        $id          = '3b8f776f-963a-4566-8d63-0f3876c7860d';
        $seriesName  = 'Formula One';
        $seriesSlug  = 'formula-one';
        $seriesYear  = 2023;
        $seriesIcon  = 'f1.svg';
        $seriesColor = '#fff';
        $venueId     = 'c958a968-a8fb-4bd9-bc33-50f73f151c67';
        $venueName   = 'Circuit Zandvoort';
        $venueSlug   = 'circuit-zandvoort';
        $countryId   = '7c775aed-873a-48fc-9ce2-533f3143b0b4';
        $countryCode = 'nl';
        $countryName = 'Netherlands';
        $reference   = '412d9c85-2d1e-47f2-9995-75905947a2bd';
        $index       = 0;
        $slug        = 'formula-one_0_zandvoort-gp';
        $name        = 'Dutch Grand Prix';
        $shortName   = 'Dutch GP';
        $shortCode   = 'ZAN';
        $startDate   = '2022-11-22 01:00:00';
        $endDate     = '2022-11-22 01:00:00';
        $sessionId   = '11e02d30-e85e-4a7a-8ab4-a6504890e437';
        $sessionType = 'race';
        $sessionSlug = 'dutchGrandPrix2022Race';

        $entity = CalendarEvent::withEntry(
            new UuidValueObject($id),
            CalendarEventSeries::fromData([
                'name'  => $seriesName,
                'slug'  => $seriesSlug,
                'year'  => $seriesYear,
                'icon'  => $seriesIcon,
                'color' => $seriesColor,
            ]),
            CalendarEventEntry::fromData([
                'venue' => [
                    'id'      => $venueId,
                    'name'    => $venueName,
                    'slug'    => $venueSlug,
                    'country' => [
                        'id'   => $countryId,
                        'code' => $countryCode,
                        'name' => $countryName,
                    ],
                ],
                'reference'  => $reference,
                'index'      => $index,
                'slug'       => $slug,
                'name'       => $name,
                'short_name' => $shortName,
                'short_code' => $shortCode,
                'status'     => null,
                'start_date' => $startDate,
                'end_date'   => $endDate,
                'sessions'   => [
                    [
                        'id'         => $sessionId,
                        'type'       => $sessionType,
                        'slug'       => $sessionSlug,
                        'has_result' => false,
                        'start_date' => $startDate,
                        'end_date'   => $endDate,
                    ],
                ],
            ]),
        );

        self::assertValueObjectSame($id, $entity->id());
        self::assertSame($seriesName, $entity->series()->data()['name']);
        self::assertSame($seriesYear, $entity->series()->data()['year']);
        self::assertSame($seriesIcon, $entity->series()->data()['icon']);
        self::assertSame($seriesColor, $entity->series()->data()['color']);
        self::assertSame($venueId, $entity->venue()->data()['id']);
        self::assertSame($venueName, $entity->venue()->data()['name']);
        self::assertSame($venueSlug, $entity->venue()->data()['slug']);
        self::assertSame($countryId, $entity->venue()->data()['country']['id']);
        self::assertSame($countryCode, $entity->venue()->data()['country']['code']);
        self::assertSame($countryName, $entity->venue()->data()['country']['name']);
        self::assertValueObjectSame($reference, $entity->reference());
        self::assertValueObjectSame($index, $entity->index());
        self::assertValueObjectSame($slug, $entity->slug());
        self::assertValueObjectSame($name, $entity->name());
        self::assertValueObjectSame($shortName, $entity->shortName());
        self::assertValueObjectSame($shortCode, $entity->shortCode());
        self::assertSame($startDate, $entity->startDate()->value()?->format('Y-m-d H:i:s'));
        self::assertSame($endDate, $entity->endDate()->value()?->format('Y-m-d H:i:s'));
        self::assertSame($sessionId, $entity->sessions()->data()[0]['id']);
        self::assertSame($sessionType, $entity->sessions()->data()[0]['type']);
        self::assertSame($sessionSlug, $entity->sessions()->data()[0]['slug']);
        self::assertFalse($entity->sessions()->data()[0]['has_result']);
    }
}
