<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Infrastructure\Persistence\Repository\ComputeLapByLapData;

use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph\Gateway\LapByLapDataGateway;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph\LapByLapData;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class LapByLapDataRepository extends CoreRepository implements LapByLapDataGateway
{
    public function findForSession(string $session, float $maxTimeRatio): LapByLapData
    {
        $qb = $this->connection->createQueryBuilder();

        $query = $qb
            ->select('DISTINCT e.car_number', 'label')
            ->addSelect('t.color', 'color')
            ->addSelect('array_agg(rl.time ORDER BY rl.lap ASC)', 'lapTimes')
            ->addSelect("min(rl.time)*{$maxTimeRatio}", 'max')
            ->addSelect('(c.finish_position+99)%100', 'finishPosition')
            ->from('race_lap', 'rl')
            ->innerJoin('entry', 'e', $qb->expr()->eq('e.id', 'rl.entry'))
            ->innerJoin('event_session', 'es', $qb->expr()->eq('es.id', 'e.session'))
            ->innerJoin('event', 'ev', $qb->expr()->eq('ev.id', 'es.event'))
            ->innerJoin('driver', 'd', $qb->expr()->eq('d.id', 'e.driver'))
            ->innerJoin('team', 't', $qb->expr()->eq('t.id', 'e.team'))
            ->innerJoin('classification', 'c', $qb->expr()->eq('c.entry', 'e.id'))
            ->where($qb->expr()->eq('e.session', ':session'))
            ->andWhere($qb->expr()->gt('rl.time', '0'))
            ->withParam('session', $session)
            ->addGroupBy('e.car_number')
            ->addGroupBy('t.color')
            ->addGroupBy('c.laps')
            ->addGroupBy('finishPosition')
            ->orderBy('finishPosition')
            ->buildQuery()
        ;

        /** @var array<array{label: string, color: string, laptimes: string, max: float}> $result */
        $result = $this->connection->execute($query)->fetchAllAssociative();

        return LapByLapData::fromData($result);
    }
}
