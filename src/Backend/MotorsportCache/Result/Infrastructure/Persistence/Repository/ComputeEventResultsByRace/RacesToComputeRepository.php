<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Infrastructure\Persistence\Repository\ComputeEventResultsByRace;

use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace\DTO\RacesToComputeDTO;
use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace\Gateway\RacesToComputeGateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class RacesToComputeRepository extends CoreRepository implements RacesToComputeGateway
{
    public function findRaces(string $eventId): RacesToComputeDTO
    {
        $qb = $this->connection->createQueryBuilder();

        $query = $qb
            ->select('es.id', 'id')
            ->select('st.label', 'type')
            ->from('event_session', 'es')
            ->innerJoin('session_type', 'st', $qb->expr()->eq('st.id', 'es.type'))
            ->where($qb->expr()->eq('es.event', ':event'))
            ->andWhere($qb->expr()->like('LOWER(st.label)', ':type'))
            ->withParam('event', $eventId)
            ->withParam('type', '%race%')
            ->orderBy('es.start_date')
            ->buildQuery()
        ;

        /** @var array<array{id: string, type: string}> $result */
        $result = $this->connection->execute($query)->fetchAllAssociative();

        return RacesToComputeDTO::fromList($result);
    }
}
