<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventStep\EventHasStepAtTheSameTimeGateway;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\EventStep;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepDateTime;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepEventId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepository;

final class EventHasStepAtTheSameTimeRepositoryUsingDoctrine extends CoreRepository implements EventHasStepAtTheSameTimeGateway
{
    /**
     * @throws NonUniqueResultException
     */
    public function eventHasStepAtTheSameTime(EventStepEventId $eventId, EventStepDateTime $dateTime): bool
    {
        $qb = $this->entityManager->createQueryBuilder();

        $query = $qb
            ->select('eventStep.id')
            ->from(EventStep::class, 'eventStep')
            ->where('eventStep.eventId = :eventId')
            ->andWhere('eventStep.dateTime = :dateTime')
            ->setParameter('dateTime', $dateTime->value())
            ->setParameter('eventId', $eventId->value())
            ->getQuery()
        ;

        try {
            $query->getSingleResult();
        } catch (NoResultException) {
            return false;
        }

        return true;
    }
}
