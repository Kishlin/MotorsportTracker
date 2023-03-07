<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportCache\Calendar\Infrastructure\Persistence\Doctrine\Repository\SyncCalendarEvents;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway\CalendarEventEntry;
use Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Doctrine\Repository\SyncCalendarEvents\FindEventsRepositoryUsingDoctrine;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Doctrine\Repository\SyncCalendarEvents\FindEventsRepositoryUsingDoctrine
 */
final class FindEventsRepositoryUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    /**
     * @throws Exception
     */
    public function testItIsEmptyWhenThereAreNoSessions(): void
    {
        $repository = new FindEventsRepositoryUsingDoctrine(self::entityManager());

        self::assertEmpty($repository->findAll(new StringValueObject('formula1'), new PositiveIntValueObject(2022)));
    }

    /**
     * @throws Exception
     */
    public function testItHasAnEmptySessionsArrayWhenThereAreNone(): void
    {
        // We are loading the event but not its sessions
        self::loadFixtures('motorsport.event.event.dutchGrandPrix2022');

        $repository = new FindEventsRepositoryUsingDoctrine(self::entityManager());

        $entries = $repository->findAll(new StringValueObject('formula1'), new PositiveIntValueObject(2022));

        self::assertIsArray($entries[0]->sessions()->data());
        self::assertEmpty($entries[0]->sessions()->data());
    }

    /**
     * @throws Exception
     */
    public function testItFindsAllEventsWithSessions(): void
    {
        self::loadFixtures(
            'motorsport.event.eventSession.dutchGrandPrix2022Race',
            'motorsport.event.eventSession.emiliaRomagnaGrandPrix2022SprintQualifying',
            'motorsport.event.eventSession.emiliaRomagnaGrandPrix2022Race',
        );

        $repository = new FindEventsRepositoryUsingDoctrine(self::entityManager());

        $entries = $repository->findAll(new StringValueObject('formula1'), new PositiveIntValueObject(2022));

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
                'slug'    => 'circuit-zandvoort',
                'country' => [
                    'code' => 'nl',
                    'name' => 'Netherlands',
                ],
            ],
            'index'      => 14,
            'slug'       => 'Dutch-gp',
            'name'       => 'Dutch GP',
            'short_name' => 'Dutch GP',
            'start_date' => '2022-09-04 13:00:00',
            'end_date'   => '2022-09-04 13:00:00',
            'sessions'   => [
                [
                    'type'       => 'race',
                    'slug'       => 'dutchGrandPrix2022Race',
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
                'slug'    => 'autodromo-internazionale-enzo-e-dino-ferrari',
                'country' => [
                    'code' => 'it',
                    'name' => 'Italia',
                ],
            ],
            'index'      => 3,
            'slug'       => 'Emilia Romagna-gp',
            'name'       => 'Emilia Romagna GP',
            'short_name' => 'Emilia Romagna GP',
            'start_date' => '2022-04-23 14:30:00',
            'end_date'   => '2022-04-24 13:00:00',
            'sessions'   => [
                [
                    'type'       => 'sprint qualifying',
                    'slug'       => 'emiliaRomagnaGrandPrix2022Sprint',
                    'has_result' => false,
                    'start_date' => '2022-04-23T14:30:00',
                    'end_date'   => '2022-04-23T15:30:00',
                ],
                [
                    'type'       => 'race',
                    'slug'       => 'emiliaRomagnaGrandPrix2022Race',
                    'has_result' => false,
                    'start_date' => '2022-04-24T13:00:00',
                    'end_date'   => '2022-04-24T14:00:00',
                ],
            ],
        ]);
    }
}
