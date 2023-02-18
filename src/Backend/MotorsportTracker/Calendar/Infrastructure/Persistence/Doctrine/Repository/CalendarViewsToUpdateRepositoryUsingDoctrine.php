<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Calendar\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation\CalendarViewsToUpdate;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation\CalendarViewsToUpdateGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class CalendarViewsToUpdateRepositoryUsingDoctrine extends DoctrineRepository implements CalendarViewsToUpdateGateway
{
    /**
     * @throws Exception
     */
    public function findForPresentation(UuidValueObject $presentationId): CalendarViewsToUpdate
    {
        $qb = $this->entityManager->getConnection()->createQueryBuilder();

        $qb->select('cv.id')
            ->from('calendar_event_step_views', 'cv')
            ->leftJoin('cv', 'championships', 'c', 'c.slug = cv.championship_slug')
            ->leftJoin('c', 'championship_presentations', 'cp', 'c.id = cp.championship')
            ->where('cp.id = :id')
            ->setParameter('id', $presentationId->value())
        ;

        /** @var array<array{id: string}> $result */
        $result = $qb->executeQuery()->fetchAllAssociative();

        return CalendarViewsToUpdate::fromScalars(array_column($result, 'id'));
    }
}
