<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Event\Infrastructure\Repository\SyncEventCache;

use Kishlin\Backend\MotorsportCache\Event\Application\SyncEventCache\EventDataDTO;
use Kishlin\Backend\MotorsportCache\Event\Application\SyncEventCache\Gateway\EventDataGateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class EventDataRepository extends CoreRepository implements EventDataGateway
{
    public function findAll(): EventDataDTO
    {
        $qb = $this->connection->createQueryBuilder();

        $query = $qb
            ->select('c.name as championship, s.year as year, e.name as event')
            ->from('event', 'e')
            ->innerJoin('season', 's', $qb->expr()->eq('e.season', 's.id'))
            ->innerJoin('championship', 'c', $qb->expr()->eq('s.championship', 'c.id'))
            ->buildQuery()
        ;

        /** @var array{championship: string, year: int, event: string}[] $result */
        $result = $this->connection->execute($query)->fetchAllAssociative();

        return EventDataDTO::fromData($result);
    }
}
