<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\ValueObject\JsonValueObject;

/**
 * @property array<string, array<array{
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
 *     additional_drivers: array{id: string, short_code: string, name: string}[],
 *     team: array{
 *         id: string,
 *         name: string,
 *         color: ?string,
 *         country: array{
 *             id: string,
 *             code: string,
 *             name: string,
 *         }|null,
 *     },
 *     car_number: int,
 *     finish_position: int,
 *     grid_position: ?int,
 *     classified_status: ?string,
 *     laps: int,
 *     points: float,
 *     race_time: float,
 *     average_lap_speed: float,
 *     best_lap_time: ?float,
 *     best_lap: ?int,
 *     best_is_fastest: ?bool,
 *     best_speed: ?float,
 *     gap_time: float,
 *     interval_time: float,
 *     gap_laps: int,
 *     interval_laps: int,
 * }>> $value
 */
final class ResultsBySession extends JsonValueObject
{
    /**
     * @return array<string, array<array{
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
     *     additional_drivers: array{id: string, short_code: string, name: string}[],
     *     team: array{
     *         id: string,
     *         name: string,
     *         color: ?string,
     *         country: array{
     *             id: string,
     *             code: string,
     *             name: string,
     *         }|null,
     *     },
     *     car_number: int,
     *     finish_position: int,
     *     grid_position: ?int,
     *     classified_status: ?string,
     *     laps: int,
     *     points: float,
     *     race_time: float,
     *     average_lap_speed: float,
     *     best_lap_time: ?float,
     *     best_lap: ?int,
     *     best_is_fastest: ?bool,
     *     best_speed: ?float,
     *     gap_time: float,
     *     interval_time: float,
     *     gap_laps: int,
     *     interval_laps: int,
     * }>>
     */
    public function data(): array
    {
        return $this->value;
    }

    /**
     * @param array<string, array<array{
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
     *     additional_drivers: array{id: string, short_code: string, name: string}[],
     *     team: array{
     *         id: string,
     *         name: string,
     *         color: ?string,
     *         country: array{
     *             id: string,
     *             code: string,
     *             name: string,
     *         }|null,
     *     },
     *     car_number: int,
     *     finish_position: int,
     *     grid_position: ?int,
     *     classified_status: ?string,
     *     laps: int,
     *     points: float,
     *     race_time: float,
     *     average_lap_speed: float,
     *     best_lap_time: ?float,
     *     best_lap: ?int,
     *     best_is_fastest: ?bool,
     *     best_speed: ?float,
     *     gap_time: float,
     *     interval_time: float,
     *     gap_laps: int,
     *     interval_laps: int,
     * }>> $result
     */
    public static function fromData(array $result): self
    {
        return new self($result);
    }
}
