<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventStep\EventHasStepWithTypeGateway;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\EventStep;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepEventId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepTypeId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepository;

final class EventHasStepWithTypeRepositoryUsingDoctrine extends CoreRepository implements EventHasStepWithTypeGateway
{
    /**
     * @throws NonUniqueResultException
     */
    public function eventHasStepWithType(EventStepEventId $eventId, EventStepTypeId $typeId): bool
    {
        $qb = $this->entityManager->createQueryBuilder();

        $query = $qb
            ->select('eventStep.id')
            ->from(EventStep::class, 'eventStep')
            ->where('eventStep.eventId = :eventId')
            ->andWhere('eventStep.typeId = :typeId')
            ->setParameter('eventId', $eventId->value())
            ->setParameter('typeId', $typeId->value())
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
