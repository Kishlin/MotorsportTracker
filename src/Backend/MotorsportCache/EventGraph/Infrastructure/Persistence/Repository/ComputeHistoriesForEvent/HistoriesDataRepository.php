<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Infrastructure\Persistence\Repository\ComputeHistoriesForEvent;

use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeHistoriesForEvent\HistoriesDataDTO;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeHistoriesForEvent\HistoriesDataGateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class HistoriesDataRepository extends CoreRepository implements HistoriesDataGateway
{
    public const SELECT_LAPS = <<<'TXT'
jsonb_agg(
    jsonb_build_object(
        'lap', rl.lap,
        'position', rl.position,
        'pit', rl.pit
    )
    ORDER BY rl.lap ASC
)
TXT;

    public function findForSession(string $session): HistoriesDataDTO
    {
        $qb = $this->connection->createQueryBuilder();

        $query = $qb
            ->select('DISTINCT e.car_number', 'car_number')
            ->addSelect('d.short_code', 'short_code')
            ->addSelect('t.color', 'color')
            ->addSelect(self::SELECT_LAPS, 'laps')
            ->addSelect('(c.finish_position+99)%100', 'finishPosition')
            ->from('race_lap', 'rl')
            ->innerJoin('entry', 'e', $qb->expr()->eq('e.id', 'rl.entry'))
            ->innerJoin('event_session', 'es', $qb->expr()->eq('es.id', 'e.session'))
            ->innerJoin('event', 'ev', $qb->expr()->eq('ev.id', 'es.event'))
            ->innerJoin('driver', 'd', $qb->expr()->eq('d.id', 'e.driver'))
            ->innerJoin('team', 't', $qb->expr()->eq('t.id', 'e.team'))
            ->innerJoin('classification', 'c', $qb->expr()->eq('c.entry', 'e.id'))
            ->where($qb->expr()->eq('e.session', ':session'))
            ->withParam('session', $session)
            ->addGroupBy('e.car_number')
            ->addGroupBy('d.short_code')
            ->addGroupBy('t.color')
            ->addGroupBy('c.laps')
            ->addGroupBy('finishPosition')
            ->orderBy('finishPosition')
            ->buildQuery()
        ;

        /** @var array<array{car_number: string, short_code: string, color: string, laps: string}> $result */
        $result = $this->connection->execute($query)->fetchAllAssociative();

        return HistoriesDataDTO::fromData($result);
    }
}
