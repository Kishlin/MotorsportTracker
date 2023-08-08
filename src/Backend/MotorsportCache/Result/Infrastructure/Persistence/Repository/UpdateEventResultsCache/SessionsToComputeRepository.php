<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Infrastructure\Persistence\Repository\UpdateEventResultsCache;

use Kishlin\Backend\MotorsportCache\Result\Application\UpdateEventResultsCache\DTO\SessionsToComputeDTO;
use Kishlin\Backend\MotorsportCache\Result\Application\UpdateEventResultsCache\Gateway\SessionsToComputeGateway;
use Kishlin\Backend\Persistence\Core\QueryBuilder\OrderBy;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class SessionsToComputeRepository extends CoreRepository implements SessionsToComputeGateway
{
    public function findSessions(string $eventId): SessionsToComputeDTO
    {
        $qb = $this->connection->createQueryBuilder();

        $query = $qb
            ->select('es.id', 'id')
            ->select('st.label', 'type')
            ->from('event_session', 'es')
            ->innerJoin('session_type', 'st', $qb->expr()->eq('st.id', 'es.type'))
            ->where($qb->expr()->eq('es.event', ':event'))
            ->withParam('event', $eventId)
            ->orderBy('es.start_date', OrderBy::DESC)
            ->buildQuery()
        ;

        /** @var array<array{id: string, type: string}> $result */
        $result = $this->connection->execute($query)->fetchAllAssociative();

        return SessionsToComputeDTO::fromList($result);
    }
}
