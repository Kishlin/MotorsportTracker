<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Calendar\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation\CalendarViewsToUpdate;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation\NewPresentationApplierGateway;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation\PresentationToApply;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CacheRepository;

final class NewPresentationApplierRepositoryUsingDoctrine extends CacheRepository implements NewPresentationApplierGateway
{
    /**
     * @throws Exception
     */
    public function applyDataToViews(CalendarViewsToUpdate $viewsToUpdate, PresentationToApply $presentationToApply): void
    {
        $this
            ->entityManager
            ->getConnection()
            ->createQueryBuilder()
            ->update('calendar_event_step_views')
            ->set('color', ':color')
            ->set('icon', ':icon')
            ->where('calendar_event_step_views.id IN (' . "'" . implode("','", $viewsToUpdate->idList()) . "'" . ')')
            ->setParameter('color', $presentationToApply->color())
            ->setParameter('icon', $presentationToApply->icon())
            ->executeQuery()
        ;
    }
}
