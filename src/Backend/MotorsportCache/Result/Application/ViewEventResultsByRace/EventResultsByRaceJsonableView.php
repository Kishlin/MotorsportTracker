<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Application\ViewEventResultsByRace;

use Kishlin\Backend\Shared\Domain\View\JsonableView;

final class EventResultsByRaceJsonableView extends JsonableView
{
    /**
     * @param array{
     *     event: string,
     *     resultsByRace: array<array{
     *         session: array{
     *             id: string,
     *             type: string,
     *         },
     *         result: array<array{
     *             driver: array{
     *                 id: string,
     *                 short_code: string,
     *                 name: string,
     *                 country: array{
     *                     id: string,
     *                     code: string,
     *                     name: string,
     *                 },
     *             },
     *             team: array{
     *                 id: string,
     *                 name: string,
     *                 color: ?string,
     *                 country: array{
     *                     id: string,
     *                     code: string,
     *                     name: string,
     *                 }|null,
     *             },
     *             car_number: int,
     *             finish_position: int,
     *             grid_position: int,
     *             classified_status: string,
     *             laps: int,
     *             points: float,
     *             race_time: float,
     *             average_lap_speed: float,
     *             best_lap_time: float,
     *             best_lap: int,
     *             best_is_fastest: bool,
     *             gap_time: float,
     *             interval_time: float,
     *             gap_laps: int,
     *             interval_laps: int,
     *         }>,
     *     }>,
     * } $results
     */
    private function __construct(
        private readonly array $results,
    ) {
    }

    /**
     * @return array{
     *     event: string,
     *     resultsByRace: array<array{
     *         session: array{
     *             id: string,
     *             type: string,
     *         },
     *         result: array<array{
     *             driver: array{
     *                 id: string,
     *                 short_code: string,
     *                 name: string,
     *                 country: array{
     *                     id: string,
     *                     code: string,
     *                     name: string,
     *                 },
     *             },
     *             team: array{
     *                 id: string,
     *                 name: string,
     *                 color: ?string,
     *                 country: array{
     *                     id: string,
     *                     code: string,
     *                     name: string,
     *                 }|null,
     *             },
     *             car_number: int,
     *             finish_position: int,
     *             grid_position: int,
     *             classified_status: string,
     *             laps: int,
     *             points: float,
     *             race_time: float,
     *             average_lap_speed: float,
     *             best_lap_time: float,
     *             best_lap: int,
     *             best_is_fastest: bool,
     *             gap_time: float,
     *             interval_time: float,
     *             gap_laps: int,
     *             interval_laps: int,
     *         }>,
     *     }>,
     * }
     */
    public function toArray(): array
    {
        return $this->results;
    }

    /**
     * @param array{
     *     event: string,
     *     resultsByRace: array<array{
     *         session: array{
     *             id: string,
     *             type: string,
     *         },
     *         result: array<array{
     *             driver: array{
     *                 id: string,
     *                 short_code: string,
     *                 name: string,
     *                 country: array{
     *                     id: string,
     *                     code: string,
     *                     name: string,
     *                 },
     *             },
     *             team: array{
     *                 id: string,
     *                 name: string,
     *                 color: ?string,
     *                 country: array{
     *                     id: string,
     *                     code: string,
     *                     name: string,
     *                 }|null,
     *             },
     *             car_number: int,
     *             finish_position: int,
     *             grid_position: int,
     *             classified_status: string,
     *             laps: int,
     *             points: float,
     *             race_time: float,
     *             average_lap_speed: float,
     *             best_lap_time: float,
     *             best_lap: int,
     *             best_is_fastest: bool,
     *             gap_time: float,
     *             interval_time: float,
     *             gap_laps: int,
     *             interval_laps: int,
     *         }>,
     *     }>,
     * } $source
     */
    public static function fromSource(array $source): self
    {
        return new self($source);
    }
}
