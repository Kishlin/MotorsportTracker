<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\SyncCalendarEvents;

use Exception;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway\CalendarEventEntry;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway\CalendarEventUpsert;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\Entity\CalendarEvent;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventSeries;
use Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\SyncCalendarEvents\SaveCalendarEventRepository;
use Kishlin\Backend\Persistence\SQL\SQLQuery;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CacheRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\SyncCalendarEvents\SaveCalendarEventRepository
 */
final class SaveCalendarEventRepositoryTest extends CacheRepositoryContractTestCase
{
    /**
     * @throws Exception
     */
    public function testItCreatesTheEventIfItDoesNotExist(): void
    {
        $repository = new SaveCalendarEventRepository(self::connection());

        $entity = $this->dutchGPCalendarEvent();

        $response = $repository->save($entity);

        self::assertSame(CalendarEventUpsert::CREATED, $response);
        self::assertCount(1, self::connection()->execute(SQLQuery::create('SELECT * FROM calendar_event'))->fetchAllAssociative());
    }

    /**
     * @throws Exception
     */
    public function testItUpdatesTheEventIfItAlreadyExists(): void
    {
        self::loadFixtures(
            'motorsport.calendar.calendarEvent.formulaOne2022DutchGP',
            'motorsport.calendar.calendarEvent.formulaOne2022EmiliaRomagnaGP',
        );

        $repository = new SaveCalendarEventRepository(self::connection());

        $entity = $this->dutchGPCalendarEvent();

        $response = $repository->save($entity);

        self::assertSame(CalendarEventUpsert::UPDATED, $response);
        self::assertCount(2, self::connection()->execute(SQLQuery::create('SELECT * FROM calendar_event'))->fetchAllAssociative());
    }

    /**
     * @throws Exception
     */
    private function dutchGPCalendarEvent(): CalendarEvent
    {
        return CalendarEvent::withEntry(
            new UuidValueObject('3b8f776f-963a-4566-8d63-0f3876c7860d'),
            CalendarEventSeries::fromData([
                'name'  => 'Formula One',
                'slug'  => 'formula-one',
                'year'  => 2023,
                'icon'  => 'f1.svg',
                'color' => '#fff',
            ]),
            CalendarEventEntry::fromData([
                'venue' => [
                    'name'    => 'Circuit Zandvoort',
                    'slug'    => 'circuit-zandvoort',
                    'country' => [
                        'code' => 'nl',
                        'name' => 'Netherlands',
                    ],
                ],
                'index'      => 0,
                'slug'       => 'formula-one_0_dutch-gp',
                'name'       => 'Dutch Grand Prix',
                'short_name' => 'Dutch GP',
                'start_date' => '2022-11-22 01:00:00',
                'end_date'   => '2022-11-22 01:00:00',
                'sessions'   => [
                    [
                        'type'       => 'race',
                        'slug'       => 'dutchGrandPrix2022Race',
                        'has_result' => false,
                        'start_date' => '2022-11-22 01:00:00',
                        'end_date'   => '2022-11-22 01:00:00',
                    ],
                ],
            ]),
        );
    }
}
