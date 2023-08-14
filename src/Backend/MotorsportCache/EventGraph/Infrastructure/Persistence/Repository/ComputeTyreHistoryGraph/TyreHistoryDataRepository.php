<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Infrastructure\Persistence\Repository\ComputeTyreHistoryGraph;

use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeTyreHistoryGraph\TyreHistoryData;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeTyreHistoryGraph\TyreHistoryDataGateway;
use Kishlin\Backend\Persistence\Core\QueryBuilder\OrderBy;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class TyreHistoryDataRepository extends CoreRepository implements TyreHistoryDataGateway
{
    public const SELECT_TYRE_DETAILS = <<<'TXT'
(
    SELECT rl2.tyre_details
    FROM race_lap rl2
    WHERE rl2.entry = rl.entry
    AND rl2.tyre_details != '[]'
    ORDER BY rl2.lap DESC
    LIMIT 1
)
TXT;

    public const SELECT_PIT_HISTORY = <<<'TXT'
(
    SELECT jsonb_agg(
        rl3.lap
        ORDER BY rl3.lap ASC
    )
    FROM race_lap rl3
    WHERE rl3.entry = rl.entry
    AND rl3.pit = true
)
TXT;

    public function findForSession(string $session): TyreHistoryData
    {
        $qb = $this->connection->createQueryBuilder();

        $query = $qb
            ->select('DISTINCT e.car_number', 'car_number')
            ->addSelect('d.short_code', 'short_code')
            ->addSelect('t.color', 'color')
            ->addSelect('max(rl.lap)', 'laps')
            ->addSelect(self::SELECT_TYRE_DETAILS, 'tyre_details')
            ->addSelect(self::SELECT_PIT_HISTORY, 'pit_history')
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
            ->addGroupBy('d.short_code')
            ->addGroupBy('t.color')
            ->addGroupBy('c.laps')
            ->addGroupBy('finishPosition')
            ->addGroupBy('rl.entry')
            ->orderBy('laps', OrderBy::DESC)
            ->addOrderBy('finishPosition')
            ->buildQuery()
        ;

        /** @var array<array{car_number: string, short_code: string, color: string, laps: int, tyre_details: null|string, pit_history: null|string, finishPosition: int}> $result */
        $result = $this->connection->execute($query)->fetchAllAssociative();

        return TyreHistoryData::fromData($result);
    }
}
