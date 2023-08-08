<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Infrastructure\Persistence\Repository\UpdateEventResultsCache;

use JsonException;
use Kishlin\Backend\MotorsportCache\Result\Application\UpdateEventResultsCache\DTO\SessionResultDTO;
use Kishlin\Backend\MotorsportCache\Result\Application\UpdateEventResultsCache\Gateway\SessionClassificationGateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class SessionClassificationRepository extends CoreRepository implements SessionClassificationGateway
{
    private const DRIVER_SELECT = <<<'TXT'
json_build_object(
    'id', d.id,
    'short_code', d.short_code,
    'name', d.name,
    'country', json_build_object(
        'id', c_d.id,
        'code', c_d.code,
        'name', c_d.name
    )
)
TXT;

    private const TEAM_SELECT = <<<'TXT'
json_build_object(
    'id', t.id,
    'name', t.name,
    'color', t.color,
    'country', case when c_t is not null
        then json_build_object(
            'id', c_t.id,
            'code', c_t.code,
            'name', c_t.name
        )
    end
)
TXT;

    /**
     * @throws JsonException
     */
    public function findResult(string $eventSessionId): SessionResultDTO
    {
        $qb = $this->connection->createQueryBuilder();

        $query = $qb
            ->select(self::DRIVER_SELECT, 'driver')
            ->select(self::TEAM_SELECT, 'team')
            ->select('e.car_number, c.finish_position, c.classified_status, c.grid_position, c.laps, c.lap_time as race_time')
            ->select('c.average_lap_speed, c.fastest_lap_time as best_lap_time, c.best_lap, c.best_is_fastest, c.best_speed')
            ->select('c.gap_time_to_lead as gap_time, c.gap_time_to_next as interval_time')
            ->select('c.gap_laps_to_lead as gap_laps, c.gap_laps_to_next as interval_laps')
            ->from('classification', 'c')
            ->innerJoin('entry', 'e', $qb->expr()->eq('e.id', 'c.entry'))
            ->innerJoin('event_session', 'es', $qb->expr()->eq('e.session', 'es.id'))
            ->innerJoin('event', 'ev', $qb->expr()->eq('es.event', 'ev.id'))
            ->innerJoin('season', 's', $qb->expr()->eq('ev.season', 's.id'))
            ->innerJoin('driver', 'd', $qb->expr()->eq('d.id', 'e.driver'))
            ->innerJoin('team', 't', $qb->expr()->eq('t.id', 'e.team'))
            ->innerJoin('country', 'c_d', $qb->expr()->eq('c_d.id', 'e.country'))
            // TODO: Caveat, the team's country will always be the driver country
            ->leftJoin('country', 'c_t', $qb->expr()->eq('c_t.id', 'e.country'))
            ->where($qb->expr()->eq('e.session', ':session'))
            ->orderBy('c.classified_status')
            ->orderBy('c.finish_position')
            ->withParam('session', $eventSessionId)
            ->buildQuery()
        ;

        /**
         * @var array<array{
         *     driver: string,
         *     team: string,
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
         * }> $result
         */
        $result = $this->connection->execute($query)->fetchAllAssociative();

        return SessionResultDTO::fromResults(
            array_map(
                static function ($raceResult) {
                    /** @var array{id: string, short_code: string, name: string, country: array{id: string, code: string, name: string}} $driver */
                    $driver = json_decode($raceResult['driver'], true, 512, JSON_THROW_ON_ERROR);

                    /** @var array{id: string, name: string, color: string, country: null|array{id: string, code: string, name: string}} $team */
                    $team = json_decode($raceResult['team'], true, 512, JSON_THROW_ON_ERROR);

                    return [
                        ...$raceResult,
                        'team'   => $team,
                        'driver' => $driver,
                    ];
                },
                $result,
            ),
        );
    }
}
