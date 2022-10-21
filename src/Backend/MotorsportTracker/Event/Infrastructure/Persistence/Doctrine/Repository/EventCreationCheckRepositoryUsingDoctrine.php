<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEvent\EventCreationCheckGateway;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\Event;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventIndex;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventLabel;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventSeasonId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class EventCreationCheckRepositoryUsingDoctrine extends DoctrineRepository implements EventCreationCheckGateway
{
    /**
     * @throws NonUniqueResultException
     */
    public function seasonHasEventWithIndexOrVenue(EventSeasonId $seasonId, EventIndex $index, EventLabel $label): bool
    {
        $qb = $this->entityManager->createQueryBuilder();

        $query = $qb
            ->select('event.id')
            ->from(Event::class, 'event')
            ->where('event.seasonId = :seasonId')
            ->andWhere($qb->expr()->orX('event.index = :index', 'event.label = :label'))
            ->setParameter('seasonId', $seasonId->value())
            ->setParameter('index', $index->value())
            ->setParameter('label', $label->value())
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
