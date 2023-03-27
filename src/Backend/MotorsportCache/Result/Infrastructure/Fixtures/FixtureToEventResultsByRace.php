<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Infrastructure\Fixtures;

use Kishlin\Backend\MotorsportCache\Result\Domain\Entity\EventResultsByRace;
use Kishlin\Backend\MotorsportCache\Result\Domain\ValueObject\ResultsByRaceValueObject;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\Fixture;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureConverter;

final class FixtureToEventResultsByRace implements FixtureConverter
{
    public function convert(Fixture $fixture): AggregateRoot
    {
        return EventResultsByRace::instance(
            new UuidValueObject($fixture->identifier()),
            new UuidValueObject($fixture->getString('event')),
            ResultsByRaceValueObject::fromData($this->decoreResults($fixture->getString('results')))
        );
    }

    /**
     * @return array<array{
     *     session: array{
     *         id: string,
     *         type: string,
     *     },
     *     result: array<array{
     *         driver: array{
     *             id: string,
     *             short_code: string,
     *             name: string,
     *             country: array{
     *                 id: string,
     *                 code: string,
     *                 name: string,
     *             },
     *         },
     *         team: array{
     *             id: string,
     *             presentation_id: string,
     *             name: string,
     *             color: string,
     *             country: array{
     *                 id: string,
     *                 code: string,
     *                 name: string,
     *             },
     *         },
     *         car_number: int,
     *         finish_position: int,
     *         grid_position: int,
     *         classified_status: string,
     *         laps: int,
     *         points: float,
     *         race_time: float,
     *         average_lap_speed: float,
     *         best_lap_time: float,
     *         best_lap: int,
     *         best_is_fastest: bool,
     *         gap_time: float,
     *         interval_time: float,
     *         gap_laps: int,
     *         interval_laps: int,
     *     }>,
     * }>
     */
    private function decoreResults(string $data): array
    {
        /**
         * @var array<array{
         *     session: array{
         *         id: string,
         *         type: string,
         *     },
         *     result: array<array{
         *         driver: array{
         *             id: string,
         *             short_code: string,
         *             name: string,
         *             country: array{
         *                 id: string,
         *                 code: string,
         *                 name: string,
         *             },
         *         },
         *         team: array{
         *             id: string,
         *             presentation_id: string,
         *             name: string,
         *             color: string,
         *             country: array{
         *                 id: string,
         *                 code: string,
         *                 name: string,
         *             },
         *         },
         *         car_number: int,
         *         finish_position: int,
         *         grid_position: int,
         *         classified_status: string,
         *         laps: int,
         *         points: float,
         *         race_time: float,
         *         average_lap_speed: float,
         *         best_lap_time: float,
         *         best_lap: int,
         *         best_is_fastest: bool,
         *         gap_time: float,
         *         interval_time: float,
         *         gap_laps: int,
         *         interval_laps: int,
         *     }>,
         * }> $decoded
         */
        $decoded = json_decode($data, true);
        assert(is_array($decoded));

        return $decoded;
    }
}
