<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Infrastructure\Persistence\Repository\ComputeLapByLapData;

use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph\EventSessionsDTO;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph\Gateway\EventRaceSessionsGateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class EventRaceRaceSessionsRepository extends CoreRepository implements EventRaceSessionsGateway
{
    public function findForEvent(string $eventId): EventSessionsDTO
    {
        $qb = $this->connection->createQueryBuilder();

        $query = $qb
            ->select('es.id as session, st.label as type')
            ->from('event_session', 'es')
            ->innerJoin('session_type', 'st', 'st.id = es.type')
            ->where($qb->expr()->eq('es.event', ':event'))
            ->andWhere($qb->expr()->like('LOWER(st.label)', ':type'))
            ->withParam('event', $eventId)
            ->withParam('type', '%race%')
            ->buildQuery()
        ;

        /** @var array<array{session: string, type: string}> $result */
        $result = $this->connection->execute($query)->fetchAllAssociative();

        return EventSessionsDTO::fromData($result);
    }
}