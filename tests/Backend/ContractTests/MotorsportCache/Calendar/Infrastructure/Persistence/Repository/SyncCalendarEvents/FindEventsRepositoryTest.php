<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\SyncCalendarEvents;

use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway\CalendarEventEntry;
use Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\SyncCalendarEvents\FindEventsRepository;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Tools\Helpers\StringHelper;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway\CalendarEventEntry
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
        self::loadFixtures('event.dutch_grand_prix_2022');

        $repository = new FindEventsRepository(self::connection());

        $entries = $repository->findAll(new StringValueObject('Formula One'), new PositiveIntValueObject(2022));

        self::assertIsArray($entries[0]->sessions()->data());
        self::assertEmpty($entries[0]->sessions()->data());
    }

    public function testItFindsAllEventsWithSessions(): void
    {
        self::loadFixtures(
            'event_session.dutch_grand_prix_2022_race',
            'event_session.emilia_romagna_grand_prix_2022_sprint_qualifying',
            'event_session.emilia_romagna_grand_prix_2022_race',
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
                'id'      => self::fixtureId('venue.zandvoort'),
                'name'    => 'Circuit Zandvoort',
                'slug'    => StringHelper::slugify('Circuit Zandvoort'),
                'country' => [
                    'id'   => self::fixtureId('country.netherlands'),
                    'code' => 'nl',
                    'name' => 'Netherlands',
                ],
            ],
            'reference'  => self::fixtureId('event.dutch_grand_prix_2022'),
            'index'      => 14,
            'name'       => 'Dutch GP',
            'slug'       => StringHelper::slugify('Formula One', '2022', '14', 'Dutch GP'),
            'short_name' => 'Dutch GP',
            'short_code' => null,
            'status'     => null,
            'start_date' => '2022-09-04 13:00:00',
            'end_date'   => '2022-09-04 13:00:00',
            'sessions'   => [
                [
                    'id'         => self::fixtureId('event_session.dutch_grand_prix_2022_race'),
                    'slug'       => StringHelper::slugify('Formula One', '2022', 'Dutch GP', 'Race'),
                    'type'       => 'race',
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
                'id'      => self::fixtureId('venue.emilia_romagna'),
                'name'    => 'Autodromo Internazionale Enzo e Dino Ferrari',
                'slug'    => StringHelper::slugify('Autodromo Internazionale Enzo e Dino Ferrari'),
                'country' => [
                    'id'   => self::fixtureId('country.italia'),
                    'code' => 'it',
                    'name' => 'Italia',
                ],
            ],
            'reference'  => self::fixtureId('event.emilia_romagna_grand_prix_2022'),
            'index'      => 3,
            'name'       => 'Emilia Romagna GP',
            'slug'       => StringHelper::slugify('Formula One', '2022', '3', 'Emilia Romagna GP'),
            'short_name' => 'Emilia Romagna GP',
            'short_code' => null,
            'status'     => null,
            'start_date' => '2022-04-23 14:30:00',
            'end_date'   => '2022-04-24 13:00:00',
            'sessions'   => [
                [
                    'id'         => self::fixtureId('event_session.emilia_romagna_grand_prix_2022_sprint_qualifying'),
                    'slug'       => StringHelper::slugify('Formula One', '2022', 'Emilia Romagna GP', 'Sprint Qualifying'),
                    'type'       => 'sprint qualifying',
                    'has_result' => false,
                    'start_date' => '2022-04-23T14:30:00',
                    'end_date'   => '2022-04-23T15:30:00',
                ],
                [
                    'id'         => self::fixtureId('event_session.emilia_romagna_grand_prix_2022_race'),
                    'slug'       => StringHelper::slugify('Formula One', '2022', 'Emilia Romagna GP', 'Race'),
                    'type'       => 'race',
                    'has_result' => false,
                    'start_date' => '2022-04-24T13:00:00',
                    'end_date'   => '2022-04-24T14:00:00',
                ],
            ],
        ]);
    }
}
