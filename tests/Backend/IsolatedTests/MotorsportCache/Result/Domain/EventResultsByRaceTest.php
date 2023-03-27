<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportCache\Result\Domain;

use Kishlin\Backend\MotorsportCache\Result\Domain\DomainEvent\EventResultsByRaceCreatedDomainEvent;
use Kishlin\Backend\MotorsportCache\Result\Domain\Entity\EventResultsByRace;
use Kishlin\Backend\MotorsportCache\Result\Domain\ValueObject\ResultsByRaceValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @coversNothing
 */
final class EventResultsByRaceTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id      = '25897793-c1d2-4ec6-a21f-7536e1aa733a';
        $event   = '7d2a8cf6-195e-4223-bb32-a1b11acddbfe';
        $results = [
            [
                'session' => [
                    'id'   => '20170b3e-0881-441e-8138-1858d23a734d',
                    'type' => 'race',
                ],
                'result' => [
                    [
                        'driver' => [
                            'id'         => '982f2c40-561b-486d-948f-5df167bce24a',
                            'short_code' => 'VER',
                            'name'       => 'Max Verstappen',
                            'country'    => [
                                'id'   => 'df8a9a2a-cf6a-4d7c-b626-14cbc25ed6aa',
                                'code' => 'nl',
                                'name' => 'Netherlands',
                            ],
                        ],
                        'team' => [
                            'id'              => '772d768e-d5cc-4b56-b5c5-2ffc547a42d8',
                            'presentation_id' => '81512fe3-3d90-40c6-b2b4-3490a5f5b0d2',
                            'name'            => 'Oracle Red Bull Racing',
                            'color'           => '#0600EF',
                            'country'         => [
                                'id'   => '61dbfbe6-0947-4fdf-ab6f-c6fada8f56d9',
                                'code' => 'au',
                                'name' => 'Austria',
                            ],
                        ],
                        'car_number'        => 1,
                        'finish_position'   => 1,
                        'grid_position'     => 1,
                        'classified_status' => 'CLA',
                        'laps'              => 57,
                        'points'            => 25,
                        'race_time'         => 5636736,
                        'average_lap_speed' => 196.861,
                        'best_lap_time'     => 96236,
                        'best_lap'          => 44,
                        'best_is_fastest'   => false,
                        'gap_time'          => 0,
                        'interval_time'     => 0,
                        'gap_laps'          => 0,
                        'interval_laps'     => 0,
                    ],
                ],
            ],
        ];

        $entity = EventResultsByRace::create(
            new UuidValueObject($id),
            new UuidValueObject($event),
            new ResultsByRaceValueObject($results),
        );

        self::assertItRecordedDomainEvents($entity, new EventResultsByRaceCreatedDomainEvent(new UuidValueObject($id)));

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($event, $entity->event());
        self::assertValueObjectSame($results, $entity->resultsByRace());
    }
}
