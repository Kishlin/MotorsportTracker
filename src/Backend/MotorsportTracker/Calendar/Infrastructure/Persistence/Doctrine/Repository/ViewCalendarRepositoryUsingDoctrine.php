<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Calendar\Infrastructure\Persistence\Doctrine\Repository;

use DateTimeImmutable;
use Doctrine\DBAL\Exception;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\ViewCalendar\JsonableCalendarView;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\ViewCalendar\ViewCalendarGateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class ViewCalendarRepositoryUsingDoctrine extends DoctrineRepository implements ViewCalendarGateway
{
    /**
     * @throws Exception
     */
    public function view(DateTimeImmutable $start, DateTimeImmutable $end): JsonableCalendarView
    {
        $qb = $this->entityManager->getConnection()->createQueryBuilder();

        $qb->select('v.championship_slug, v.color, v.icon, v.name, v.type, v.venue_label, v.date_time, v.reference')
            ->from('calendar_event_step_views', 'v')
            ->where('v.date_time >= :firstDay')
            ->andWhere('v.date_time <= :lastDay')
            ->setParameter('lastDay', $end->format('Y-m-d H:i:s'))
            ->setParameter('firstDay', $start->format('Y-m-d H:i:s'))
        ;

        /** @var array<array{championship_slug: string, color: string, icon: string, name: string, type: string, venue_label: string, date_time: string, reference: string}> $result */
        $result = $qb->executeQuery()->fetchAllAssociative();

        return JsonableCalendarView::fromSource($result);
    }
}