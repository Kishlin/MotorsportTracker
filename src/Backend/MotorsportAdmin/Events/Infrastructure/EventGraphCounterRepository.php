<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportAdmin\Events\Infrastructure;

use Kishlin\Backend\MotorsportAdmin\Events\Application\ListEvents\EventGraphCounter;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CacheRepositoryInterface;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\ReadRepository;

final readonly class EventGraphCounterRepository extends ReadRepository implements EventGraphCounter, CacheRepositoryInterface
{
    public function graphsForEvent(string $event): int
    {
        $qb = $this->createQueryBuilder();

        $qb
            ->select('count(g.id)')
            ->from('event_graph', 'g')
            ->where($qb->expr()->eq('g.event', ':event'))
            ->withParam('event', $event)
        ;

        $ret = $this->connection->execute($qb->buildQuery())->fetchOne();
        assert(is_int($ret));

        return $ret;
    }
}
