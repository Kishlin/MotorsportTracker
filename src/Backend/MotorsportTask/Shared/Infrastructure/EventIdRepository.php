<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Shared\Infrastructure;

use Kishlin\Backend\MotorsportTask\Shared\Application\EventIdGateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepositoryInterface;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\ReadRepository;

final readonly class EventIdRepository extends ReadRepository implements CoreRepositoryInterface, EventIdGateway
{
    public function findEventId(string $series, int $year, string $event): ?string
    {
        $qb = $this->connection->createQueryBuilder();

        $query = $qb
            ->select('e.id', 'id')
            ->from('event', 'e')
            ->innerJoin('season', 's', 'e.season = s.id')
            ->innerJoin('series', 'se', 's.series = se.id')
            ->andWhere($qb->expr()->eq('se.name', ':series'))
            ->andWhere($qb->expr()->eq('s.year', ':year'))
            ->where($qb->expr()->eq('e.name', ':event'))
            ->withParam('series', $series)
            ->withParam('event', $event)
            ->withParam('year', $year)
            ->limit(1)
            ->buildQuery()
        ;

        $result = $this->connection->execute($query)->fetchOne();

        if (false === is_string($result)) {
            return null;
        }

        return $result;
    }
}
