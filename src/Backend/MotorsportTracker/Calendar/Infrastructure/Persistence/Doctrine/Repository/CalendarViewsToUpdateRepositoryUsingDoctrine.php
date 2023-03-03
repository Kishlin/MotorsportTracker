<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Calendar\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation\CalendarViewsToUpdate;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation\CalendarViewsToUpdateGateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CacheRepository;

final class CalendarViewsToUpdateRepositoryUsingDoctrine extends CacheRepository implements CalendarViewsToUpdateGateway
{
    /**
     * @throws Exception
     */
    public function findForSlug(string $slug): CalendarViewsToUpdate
    {
        $qb = $this->entityManager->getConnection()->createQueryBuilder();

        $qb->select('cv.id')
            ->from('calendar_event_step_views', 'cv')
            ->where('cv.championship_slug = :slug')
            ->setParameter('slug', $slug)
        ;

        /** @var array<array{id: string}> $result */
        $result = $qb->executeQuery()->fetchAllAssociative();

        return CalendarViewsToUpdate::fromScalars(array_column($result, 'id'));
    }
}
