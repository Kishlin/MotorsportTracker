<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Infrastructure\Persistence\Repository;

use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph\EventSessionsDTO;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Gateway\RaceAndSprintSessionsGateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class RaceAndSprintSessionsRepository extends CoreRepository implements RaceAndSprintSessionsGateway
{
    public function findForEvent(string $eventId): EventSessionsDTO
    {
        $qb = $this->connection->createQueryBuilder();

        $query = $qb
            ->select('es.id as session, st.label as type')
            ->from('event_session', 'es')
            ->innerJoin('session_type', 'st', 'st.id = es.type')
            ->where($qb->expr()->eq('es.event', ':event'))
            ->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('LOWER(st.label)', ':type'),
                    $qb->expr()->eq('LOWER(st.label)', ':sprint'),
                )
            )
            ->withParam('sprint', 'sprint')
            ->withParam('event', $eventId)
            ->withParam('type', '%race%')
            ->orderBy('st.label')
            ->buildQuery()
        ;

        /** @var array<array{session: string, type: string}> $result */
        $result = $this->connection->execute($query)->fetchAllAssociative();

        return EventSessionsDTO::fromData($result);
    }
}
