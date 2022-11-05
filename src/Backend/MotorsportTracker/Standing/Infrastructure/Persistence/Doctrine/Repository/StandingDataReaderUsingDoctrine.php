<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\MotorsportTracker\Standing\Application\RefreshDriverStandingsOnResultsRecorded\StandingDataDTO;
use Kishlin\Backend\MotorsportTracker\Standing\Application\RefreshDriverStandingsOnResultsRecorded\StandingDataReader;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class StandingDataReaderUsingDoctrine extends DoctrineRepository implements StandingDataReader
{
    /**
     * @throws Exception
     *
     * @return StandingDataDTO[]
     */
    public function findStandingDataForEvent(string $eventId): array
    {
        $query = <<<'SQL'
SELECT racer.driver, c.team, SUM(result.points) as points
FROM events refEvent
JOIN (
    select e.season, e.index, e.id from events e
) as event on event.season = refEvent.season and event.index <= refEvent.index
JOIN event_steps es on event.id = es.event
JOIN results result on es.id = result.event_step
JOIN racers racer on result.racer = racer.id
JOIN cars c on racer.car = c.id
WHERE refEvent.id = :eventId
GROUP BY racer.driver, c.team
SQL;

        /** @var array<array{driver: string, team: string, points: float}> $result */
        $result = $this
            ->entityManager
            ->getConnection()
            ->executeQuery($query, ['eventId' => $eventId])
            ->fetchAllAssociative()
        ;

        return array_map(
            static function (array $data) {
                return StandingDataDTO::fromScalars($data['driver'], $data['team'], 0.0, (float) ($data['points']));
            },
            $result
        );
    }
}
