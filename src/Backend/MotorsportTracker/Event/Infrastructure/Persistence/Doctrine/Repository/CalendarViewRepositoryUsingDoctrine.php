<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\MotorsportTracker\Event\Application\ViewCalendar\CalendarViewGateway;
use Kishlin\Backend\MotorsportTracker\Event\Domain\View\JsonableCalendarView;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class CalendarViewRepositoryUsingDoctrine extends DoctrineRepository implements CalendarViewGateway
{
    /**
     * @throws Exception
     */
    public function view(): JsonableCalendarView
    {
        $qb = $this->entityManager->getConnection()->createQueryBuilder();

        $qb->select('es.date_time, s.championship, e.venue, es.type, es.event')
            ->from('event_steps', 'es')
            ->join('es', 'events', 'e', 'es.event = e.id')
            ->join('e', 'seasons', 's', 'e.season = s.id')
        ;

        /** @var array<array{date_time: string, championship: string, venue: string, type: string, event: string}> $result */
        $result = $qb->executeQuery()->fetchAllAssociative();

        return JsonableCalendarView::fromSource($result);
    }
}
