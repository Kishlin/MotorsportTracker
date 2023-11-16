<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Infrastructure\Repository;

use Kishlin\Backend\MotorsportETL\Shared\Domain\SessionWithResultListGateway;
use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\EventsFilter;
use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\SessionIdentity;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepositoryInterface;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\ReadRepository;

final readonly class SessionWithResultListRepository extends ReadRepository implements CoreRepositoryInterface, SessionWithResultListGateway
{
    public function find(EventsFilter $eventsFilter): array
    {
        $qb = $this->createQueryBuilder();

        $qb
            ->select('es.id', 'id')
            ->addSelect('es.ref', 'ref')
            ->addSelect('s.id', 'season')
            ->from('event_session', 'es')
            ->innerJoin('event', 'e', $qb->expr()->eq('es.event', 'e.id'))
            ->innerJoin('season', 's', $qb->expr()->eq('e.season', 's.id'))
            ->innerJoin('series', 'se', $qb->expr()->eq('s.series', 'se.id'))
            ->where($qb->expr()->eq('se.name', ':seriesName'))
            ->andWhere($qb->expr()->eq('s.year', ':year'))
            ->andWhere('es.has_result is true')
            ->withParam('seriesName', $eventsFilter->seriesName())
            ->withParam('year', $eventsFilter->year())
        ;

        $event = $eventsFilter->eventFilter();
        if (null !== $event) {
            $qb
                ->andWhere($qb->expr()->eq('e.name', ':event'))
                ->withParam('event', $event)
            ;
        }

        /** @var array{id: string, ref: string, season: string}[] $ret */
        $ret = $this->connection->execute($qb->buildQuery())->fetchAllAssociative();

        return array_map(
            static function (array $datum) {
                return SessionIdentity::fromData($datum);
            },
            $ret,
        );
    }
}
