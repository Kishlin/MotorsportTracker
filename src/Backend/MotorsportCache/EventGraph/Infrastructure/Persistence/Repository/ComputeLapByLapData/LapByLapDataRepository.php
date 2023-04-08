<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Infrastructure\Persistence\Repository\ComputeLapByLapData;

use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph\Gateway\LapByLapDataGateway;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph\LapByLapData;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class LapByLapDataRepository extends CoreRepository implements LapByLapDataGateway
{
    public function findForSession(string $session): LapByLapData
    {
        $qb = $this->connection->createQueryBuilder();

        $teamPresentationJoinCondition = $qb->expr()->andX(
            $qb->expr()->eq('tp.team', 't.id'),
            $qb->expr()->eq('tp.season', 'ev.season'),
        );

        $query = $qb
            ->select('d.short_code', 'label')
            ->addSelect('tp.color', 'color')
            ->addSelect('c.laps', 'laps')
            ->addSelect('array_agg(rl.time ORDER BY rl.lap ASC)', 'lapTimes')
            ->from('race_lap', 'rl')
            ->innerJoin('entry', 'e', $qb->expr()->eq('e.id', 'rl.entry'))
            ->innerJoin('event_session', 'es', $qb->expr()->eq('es.id', 'e.session'))
            ->innerJoin('event', 'ev', $qb->expr()->eq('ev.id', 'es.event'))
            ->innerJoin('driver', 'd', $qb->expr()->eq('d.id', 'e.driver'))
            ->innerJoin('team', 't', $qb->expr()->eq('t.id', 'e.team'))
            ->innerJoin('classification', 'c', $qb->expr()->eq('c.entry', 'e.id'))
            ->innerJoin('team_presentation', 'tp', $teamPresentationJoinCondition)
            ->where($qb->expr()->eq('e.session', ':session'))
            ->withParam('session', $session)
            ->addGroupBy('d.short_code')
            ->addGroupBy('tp.color')
            ->addGroupBy('c.laps')
            ->addGroupBy('c.finish_position')
            ->orderBy('(c.finish_position+99)%100')
            ->buildQuery()
        ;

        /** @var array<array{label: string, color: string, laps: int, laptimes: string}> $result */
        $result = $this->connection->execute($query)->fetchAllAssociative();

        return LapByLapData::fromData($result);
    }
}
