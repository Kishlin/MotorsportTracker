<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Infrastructure\Persistence\Repository\ComputeFastestLapDeltaGraph;

use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeFastestLapDeltaGraph\FastestLapDeltaData;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeFastestLapDeltaGraph\FastestLapDeltaDataGateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class FastestLapDeltaDataRepository extends CoreRepository implements FastestLapDeltaDataGateway
{
    public function findForSession(string $session): FastestLapDeltaData
    {
        $qb = $this->connection->createQueryBuilder();

        $query = $qb
            ->select('DISTINCT e.car_number', 'car_number')
            ->addSelect('d.short_code', 'short_code')
            ->addSelect('t.color', 'color')
            ->addSelect('c.fastest_lap_time', 'fastest')
            ->from('classification', 'c')
            ->innerJoin('entry', 'e', $qb->expr()->eq('e.id', 'c.entry'))
            ->innerJoin('event_session', 'es', $qb->expr()->eq('es.id', 'e.session'))
            ->innerJoin('event', 'ev', $qb->expr()->eq('ev.id', 'es.event'))
            ->innerJoin('driver', 'd', $qb->expr()->eq('d.id', 'e.driver'))
            ->innerJoin('team', 't', $qb->expr()->eq('t.id', 'e.team'))
            ->where($qb->expr()->eq('e.session', ':session'))
            ->andWhere($qb->expr()->gt('c.fastest_lap_time', '0'))
            ->withParam('session', $session)
            ->addGroupBy('e.car_number')
            ->addGroupBy('d.short_code')
            ->addGroupBy('t.color')
            ->addGroupBy('c.entry')
            ->addGroupBy('fastest')
            ->orderBy('fastest')
            ->buildQuery()
        ;

        /** @var array<array{car_number: string, short_code: string, color: string, fastest: int}> $result */
        $result = $this->connection->execute($query)->fetchAllAssociative();

        return FastestLapDeltaData::fromData($result);
    }
}
