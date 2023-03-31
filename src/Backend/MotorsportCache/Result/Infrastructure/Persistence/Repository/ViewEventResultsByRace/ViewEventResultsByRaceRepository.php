<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Infrastructure\Persistence\Repository\ViewEventResultsByRace;

use JsonException;
use Kishlin\Backend\MotorsportCache\Result\Application\ViewEventResultsByRace\EventResultsByRaceJsonableView;
use Kishlin\Backend\MotorsportCache\Result\Application\ViewEventResultsByRace\ViewEventResultsByRaceGateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CacheRepository;

final class ViewEventResultsByRaceRepository extends CacheRepository implements ViewEventResultsByRaceGateway
{
    /**
     * @throws JsonException
     */
    public function viewForEvent(string $event): EventResultsByRaceJsonableView
    {
        $qb = $this->connection->createQueryBuilder();

        $query = $qb
            ->select('event, results_by_race as results')
            ->from('event_results_by_race')
            ->where($qb->expr()->eq('event', ':event'))
            ->withParam('event', $event)
            ->limit(1)
            ->buildQuery()
        ;

        /** @var array{event: string, results: string}|array{} $result */
        $result = $this->connection->execute($query)->fetchAssociative();

        if (empty($result)) {
            return EventResultsByRaceJsonableView::fromSource(['event' => $event, 'resultsByRace' => []]);
        }

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
         *             color: ?string,
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
         * }> $decodedResults
         */
        $decodedResults = json_decode($result['results'], true, 512, JSON_THROW_ON_ERROR);

        return EventResultsByRaceJsonableView::fromSource([
            'event'         => $event,
            'resultsByRace' => $decodedResults,
        ]);
    }
}
