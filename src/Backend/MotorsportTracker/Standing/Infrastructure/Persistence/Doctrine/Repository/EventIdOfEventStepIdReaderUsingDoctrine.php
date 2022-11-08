<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\MotorsportTracker\Standing\Application\RefreshStandingsOnResultsRecorded\EventIdOfEventStepIdReader;
use Kishlin\Backend\MotorsportTracker\Standing\Application\RefreshStandingsOnResultsRecorded\EventNotFoundForEventStepException;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class EventIdOfEventStepIdReaderUsingDoctrine extends DoctrineRepository implements EventIdOfEventStepIdReader
{
    /**
     * @throws Exception
     */
    public function eventIdForEventStepId(UuidValueObject $eventStepId): string
    {
        $qb = $this->entityManager->getConnection()->createQueryBuilder();

        $qb->select('es.event')
            ->from('event_steps', 'es')
            ->where('es.id = :eventStepId')
            ->groupBy('es.event')
            ->setParameter('eventStepId', $eventStepId->value())
        ;

        $result = $qb->executeQuery()->fetchOne();

        if (false === is_string($result)) {
            throw new EventNotFoundForEventStepException();
        }

        return $result;
    }
}
