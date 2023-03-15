<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\SyncCalendarEvents;

use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway\CalendarEventEntry;
use Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\SyncCalendarEvents\FindEventsRepository;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\SyncCalendarEvents\FindEventsRepository
 */
final class FindEventsRepositoryTest extends CoreRepositoryContractTestCase
{
    public function testItIsEmptyWhenThereAreNoSessions(): void
    {
        $repository = new FindEventsRepository(self::connection());

        self::assertEmpty($repository->findAll(new StringValueObject('Formula One'), new PositiveIntValueObject(2022)));
    }

    public function testItHasAnEmptySessionsArrayWhenThereAreNone(): void
    {
        // We are loading the event but not its sessions
        self::loadFixtures('motorsport.event.event.dutchGrandPrix2022');

        $repository = new FindEventsRepository(self::connection());

        $entries = $repository->findAll(new StringValueObject('Formula One'), new PositiveIntValueObject(2022));

        self::assertIsArray($entries[0]->sessions()->data());
        self::assertEmpty($entries[0]->sessions()->data());
    }

    public function testItFindsAllEventsWithSessions(): void
    {
        self::loadFixtures(
            'motorsport.event.eventSession.dutchGrandPrix2022Race',
            'motorsport.event.eventSession.emiliaRomagnaGrandPrix2022SprintQualifying',
            'motorsport.event.eventSession.emiliaRomagnaGrandPrix2022Race',
        );

        $repository = new FindEventsRepository(self::connection());

        $entries = $repository->findAll(new StringValueObject('Formula One'), new PositiveIntValueObject(2022));

        self::assertEqualsCanonicalizing(
            [$this->dutchGPCalendarEntry(), $this->emiliaRomagnaGPCalendarEntry()],
            $entries,
        );
    }

    private function dutchGPCalendarEntry(): CalendarEventEntry
    {
        return CalendarEventEntry::fromData([
            'venue' => [
                'name'    => 'Circuit Zandvoort',
                'slug'    => 'Circuit Zandvoort',
                'country' => [
                    'code' => 'nl',
                    'name' => 'Netherlands',
                ],
            ],
            'index'      => 14,
            'slug'       => 'Dutch GP',
            'name'       => 'Dutch GP',
            'short_name' => 'Dutch GP',
            'start_date' => '2022-09-04 13:00:00',
            'end_date'   => '2022-09-04 13:00:00',
            'sessions'   => [
                [
                    'type'       => 'race',
                    'slug'       => 'Dutch GP race',
                    'has_result' => false,
                    'start_date' => '2022-09-04T13:00:00',
                    'end_date'   => '2022-09-04T14:00:00',
                ],
            ],
        ]);
    }

    private function emiliaRomagnaGPCalendarEntry(): CalendarEventEntry
    {
        return CalendarEventEntry::fromData([
            'venue' => [
                'name'    => 'Autodromo Internazionale Enzo e Dino Ferrari',
                'slug'    => 'Autodromo Internazionale Enzo e Dino Ferrari',
                'country' => [
                    'code' => 'it',
                    'name' => 'Italia',
                ],
            ],
            'index'      => 3,
            'slug'       => 'Emilia Romagna GP',
            'name'       => 'Emilia Romagna GP',
            'short_name' => 'Emilia Romagna GP',
            'start_date' => '2022-04-23 14:30:00',
            'end_date'   => '2022-04-24 13:00:00',
            'sessions'   => [
                [
                    'type'       => 'sprint qualifying',
                    'slug'       => 'Emilia Romagna GP sprint qualifying',
                    'has_result' => false,
                    'start_date' => '2022-04-23T14:30:00',
                    'end_date'   => '2022-04-23T15:30:00',
                ],
                [
                    'type'       => 'race',
                    'slug'       => 'Emilia Romagna GP race',
                    'has_result' => false,
                    'start_date' => '2022-04-24T13:00:00',
                    'end_date'   => '2022-04-24T14:00:00',
                ],
            ],
        ]);
    }
}
