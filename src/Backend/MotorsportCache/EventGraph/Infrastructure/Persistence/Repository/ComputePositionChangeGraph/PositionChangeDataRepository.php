<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Infrastructure\Persistence\Repository\ComputePositionChangeGraph;

use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputePositionChangeGraph\PositionChangeData;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputePositionChangeGraph\PositionChangeDataGateway;
use Kishlin\Backend\Persistence\Core\QueryBuilder\OrderBy;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class PositionChangeDataRepository extends CoreRepository implements PositionChangeDataGateway
{
    public function findForSession(string $session): PositionChangeData
    {
        $qb = $this->connection->createQueryBuilder();

        $query = $qb
            ->select('DISTINCT e.car_number', 'car_number')
            ->addSelect('d.short_code', 'short_code')
            ->addSelect('t.color', 'color')
            ->addSelect('c.grid_position', 'grid')
            ->addSelect('c.finish_position', 'finish')
            ->addSelect('c.grid_position - c.finish_position', 'changes')
            ->from('classification', 'c')
            ->innerJoin('entry', 'e', $qb->expr()->eq('e.id', 'c.entry'))
            ->innerJoin('event_session', 'es', $qb->expr()->eq('es.id', 'e.session'))
            ->innerJoin('event', 'ev', $qb->expr()->eq('ev.id', 'es.event'))
            ->innerJoin('driver', 'd', $qb->expr()->eq('d.id', 'e.driver'))
            ->innerJoin('team', 't', $qb->expr()->eq('t.id', 'e.team'))
            ->where($qb->expr()->eq('e.session', ':session'))
            ->andWhere($qb->expr()->eq('c.classified_status', "'CLA'"))
            ->withParam('session', $session)
            ->addGroupBy('e.car_number')
            ->addGroupBy('d.short_code')
            ->addGroupBy('t.color')
            ->addGroupBy('c.entry')
            ->addGroupBy('grid')
            ->addGroupBy('finish')
            ->addGroupBy('changes')
            ->orderBy('changes', OrderBy::DESC)
            ->addOrderBy('finish')
            ->buildQuery()
        ;

        /** @var array<array{car_number: string, short_code: string, color: string, grid: int, finish: int, changes: int}> $result */
        $result = $this->connection->execute($query)->fetchAllAssociative();

        return PositionChangeData::fromData($result);
    }
}
