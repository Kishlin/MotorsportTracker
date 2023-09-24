<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\Persistence;

use Kishlin\Backend\MotorsportStatsScrapper\Domain\DTO\SessionsListDTO;
use Kishlin\Backend\MotorsportStatsScrapper\Domain\Gateway\SessionsListGateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class SessionsListRepository extends CoreRepository implements SessionsListGateway
{
    public function allSessions(string $championship, int $year, ?string $event): SessionsListDTO
    {
        $qb = $this->connection->createQueryBuilder();

        $qb
            ->select('es.id, es.ref, e.id as event, s.id as season')
            ->addSelect('st.label', 'type')
            ->from('event_session', 'es')
            ->innerJoin('event', 'e', $qb->expr()->eq('e.id', 'es.event'))
            ->innerJoin('season', 's', $qb->expr()->eq('s.id', 'e.season'))
            ->innerJoin('session_type', 'st', $qb->expr()->eq('st.id', 'es.type'))
            ->innerJoin('series', 'c', $qb->expr()->eq('c.id', 's.series'))
            ->where($qb->expr()->eq('c.name', ':championship'))
            ->andWhere($qb->expr()->eq('s.year', ':year'))
            ->andWhere('es.has_result is true')
            ->withParam('championship', $championship)
            ->withParam('year', $year)
        ;

        if (null !== $event) {
            $qb
                ->andWhere($qb->expr()->eq('e.name', ':event'))
                ->withParam('event', $event)
            ;
        }

        /** @var array<array{id: string, ref: string, event: string, season: string}> $ret */
        $ret = $this->connection->execute($qb->buildQuery())->fetchAllAssociative();

        return SessionsListDTO::fromData($ret);
    }
}
