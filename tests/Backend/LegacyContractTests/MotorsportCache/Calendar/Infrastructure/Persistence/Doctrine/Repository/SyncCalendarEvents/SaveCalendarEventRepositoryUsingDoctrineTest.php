<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportCache\Calendar\Infrastructure\Persistence\Doctrine\Repository\SyncCalendarEvents;

use Exception;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway\CalendarEventEntry;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway\CalendarEventUpsert;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\Entity\CalendarEvent;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventSeries;
use Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\SyncCalendarEvents\SaveCalendarEventRepositoryUsingDoctrine;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CacheLegacyRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\SyncCalendarEvents\SaveCalendarEventRepositoryUsingDoctrine
 */
final class SaveCalendarEventRepositoryUsingDoctrineTest extends CacheLegacyRepositoryContractTestCase
{
    /**
     * @throws Exception
     */
    public function testItCreatesTheEventIfItDoesNotExist(): void
    {
        $repository = new SaveCalendarEventRepositoryUsingDoctrine(self::entityManager());

        $entity = $this->dutchGPCalendarEvent();

        $response = $repository->save($entity);

        self::assertSame(CalendarEventUpsert::CREATED, $response);
        self::assertCount(1, self::entityManager()->getRepository(CalendarEvent::class)->findAll());
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

        $repository = new SaveCalendarEventRepositoryUsingDoctrine(self::entityManager());

        $entity = $this->dutchGPCalendarEvent();

        $response = $repository->save($entity);

        self::assertSame(CalendarEventUpsert::UPDATED, $response);
        self::assertCount(2, self::entityManager()->getRepository(CalendarEvent::class)->findAll());
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
