<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\Persistence;

use Kishlin\Backend\MotorsportStatsScrapper\Domain\DTO\SessionDTO;
use Kishlin\Backend\MotorsportStatsScrapper\Domain\Gateway\SessionGateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;
use RuntimeException;

final class SessionRepository extends CoreRepository implements SessionGateway
{
    public function find(string $championshipName, int $year, string $event, string $sessionType): ?SessionDTO
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select('es.id, es.ref, e.id as event, s.id as season')
            ->from('event_session', 'es')
            ->innerJoin('event', 'e', $qb->expr()->eq('e.id', 'es.event'))
            ->innerJoin('season', 's', $qb->expr()->eq('s.id', 'e.season'))
            ->innerJoin('session_type', 'st', $qb->expr()->eq('st.id', 'es.type'))
            ->innerJoin('series', 'c', $qb->expr()->eq('c.id', 's.series'))
            ->andWhere($qb->expr()->eq('st.label', ':type'))
            ->andWhere($qb->expr()->eq('e.name', ':event'))
            ->andWhere($qb->expr()->eq('s.year', ':year'))
            ->andWhere($qb->expr()->eq('c.name', ':name'))
            ->withParam('name', $championshipName)
            ->withParam('type', $sessionType)
            ->withParam('event', $event)
            ->withParam('year', $year)
        ;

        /** @var array<array{id: string, ref: string, event: string, season: string}> $result */
        $result = $this->connection->execute($qb->buildQuery())->fetchAllAssociative();

        if (1 < count($result)) {
            throw new RuntimeException('Multiple sessions match.');
        }

        if (empty($result)) {
            return null;
        }

        return SessionDTO::fromData($result[0]);
    }
}
