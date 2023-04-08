<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Infrastructure\Persistence\Repository\ViewGraphDataForEvent;

use JsonException;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\ViewGraphDataForEvent\GraphDataForEventGateway;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\ViewGraphDataForEvent\GraphDataForEventJsonableView;
use Kishlin\Backend\Persistence\Core\QueryBuilder\OrderBy;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CacheRepository;

final class GraphDataForEventRepository extends CacheRepository implements GraphDataForEventGateway
{
    /**
     * @throws JsonException
     */
    public function viewForEvent(string $event): GraphDataForEventJsonableView
    {
        $qb = $this->connection->createQueryBuilder();

        $query = $qb
            ->select('type')
            ->addSelect('data')
            ->from('event_graph')
            ->where($qb->expr()->eq('event', ':event'))
            ->withParam('event', $event)
            ->orderBy('event_graph.order', OrderBy::DESC)
            ->buildQuery()
        ;

        /** @var array<array{type: string, data: string}> $result */
        $result = $this->connection->execute($query)->fetchAllAssociative();

        return GraphDataForEventJsonableView::fromSource($result);
    }
}
