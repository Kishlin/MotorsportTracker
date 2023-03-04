<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Event\Application\SearchEventStepIdAndDateTime\SearchEventStepIdAndDateTimeViewer;
use Kishlin\Backend\MotorsportTracker\Event\Domain\View\EventStepIdAndDateTimePOPO;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepository;

final class SearchEventStepIdAndDateTimeViewerUsingDoctrine extends CoreRepository implements SearchEventStepIdAndDateTimeViewer
{
    /**
     * @throws Exception|NonUniqueResultException
     */
    public function search(string $seasonId, string $keyword, string $eventType): ?EventStepIdAndDateTimePOPO
    {
        $qb = $this->entityManager->getConnection()->createQueryBuilder();

        $qb->select('es.id as id, es.date_time as date_time')
            ->from('event_steps', 'es')
            ->leftJoin('es', 'step_types', 'st', 'es.type = st.id')
            ->leftJoin('es', 'events', 'e', 'es.event = e.id')
            ->leftJoin('e', 'venues', 'v', 'e.venue = v.id')
            ->where("LOWER(REPLACE(CONCAT(e.name, v.name), ' ', '')) LIKE LOWER(REPLACE(:keyword, ' ', ''))")
            ->andWhere("LOWER(REPLACE(st.label, ' ', '')) LIKE LOWER(REPLACE(:eventType, ' ', ''))")
            ->andWhere('e.season = :seasonId')
            ->setParameter('eventType', "%{$eventType}%")
            ->setParameter('keyword', "%{$keyword}%")
            ->setParameter('seasonId', $seasonId)
        ;

        /** @var array<array{id: string, date_time: string}> $result */
        $result = $qb->executeQuery()->fetchAllAssociative();

        if (0 === count($result)) {
            return null;
        }

        if (1 !== count($result)) {
            throw new NonUniqueResultException();
        }

        return EventStepIdAndDateTimePOPO::fromData($result[0]);
    }
}
