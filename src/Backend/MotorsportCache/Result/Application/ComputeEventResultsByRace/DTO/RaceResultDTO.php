<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace\DTO;

final class RaceResultDTO
{
    /**
     * @param array<array{
     *     driver: array{
     *         id: string,
     *         short_code: string,
     *         name: string,
     *         country: array{
     *             id: string,
     *             code: string,
     *             name: string,
     *         },
     *     },
     *     team: array{
     *         id: string,
     *         presentation_id: string,
     *         name: string,
     *         color: ?string,
     *         country: array{
     *             id: string,
     *             code: string,
     *             name: string,
     *         },
     *     },
     *     car_number: int,
     *     finish_position: int,
     *     grid_position: int,
     *     classified_status: string,
     *     laps: int,
     *     points: float,
     *     race_time: float,
     *     average_lap_speed: float,
     *     best_lap_time: float,
     *     best_lap: int,
     *     best_is_fastest: bool,
     *     gap_time: float,
     *     interval_time: float,
     *     gap_laps: int,
     *     interval_laps: int,
     * }> $result
     */
    private function __construct(
        private readonly array $result,
    ) {
    }

    /**
     * @return array<array{
     *     driver: array{
     *         id: string,
     *         short_code: string,
     *         name: string,
     *         country: array{
     *             id: string,
     *             code: string,
     *             name: string,
     *         },
     *     },
     *     team: array{
     *         id: string,
     *         presentation_id: string,
     *         name: string,
     *         color: ?string,
     *         country: array{
     *             id: string,
     *             code: string,
     *             name: string,
     *         },
     *     },
     *     car_number: int,
     *     finish_position: int,
     *     grid_position: int,
     *     classified_status: string,
     *     laps: int,
     *     points: float,
     *     race_time: float,
     *     average_lap_speed: float,
     *     best_lap_time: float,
     *     best_lap: int,
     *     best_is_fastest: bool,
     *     gap_time: float,
     *     interval_time: float,
     *     gap_laps: int,
     *     interval_laps: int,
     * }>
     */
    public function results(): array
    {
        return $this->result;
    }

    /**
     * @param array<array{
     *     driver: array{
     *         id: string,
     *         short_code: string,
     *         name: string,
     *         country: array{
     *             id: string,
     *             code: string,
     *             name: string,
     *         },
     *     },
     *     team: array{
     *         id: string,
     *         presentation_id: string,
     *         name: string,
     *         color: ?string,
     *         country: array{
     *             id: string,
     *             code: string,
     *             name: string,
     *         },
     *     },
     *     car_number: int,
     *     finish_position: int,
     *     grid_position: int,
     *     classified_status: string,
     *     laps: int,
     *     points: float,
     *     race_time: float,
     *     average_lap_speed: float,
     *     best_lap_time: float,
     *     best_lap: int,
     *     best_is_fastest: bool,
     *     gap_time: float,
     *     interval_time: float,
     *     gap_laps: int,
     *     interval_laps: int,
     * }> $result
     */
    public static function fromResults(array $result): self
    {
        return new self($result);
    }
}