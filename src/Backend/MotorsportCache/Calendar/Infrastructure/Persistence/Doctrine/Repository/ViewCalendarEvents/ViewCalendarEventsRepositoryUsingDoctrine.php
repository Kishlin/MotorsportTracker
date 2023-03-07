<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Doctrine\Repository\ViewCalendarEvents;

use DateTimeImmutable;
use Doctrine\DBAL\Exception;
use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewCalendarEvents\JsonableCalendarEventsView;
use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewCalendarEvents\ViewCalendarEventsGateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CacheRepository;

final class ViewCalendarEventsRepositoryUsingDoctrine extends CacheRepository implements ViewCalendarEventsGateway
{
    /**
     * @throws Exception
     */
    public function view(DateTimeImmutable $start, DateTimeImmutable $end): JsonableCalendarEventsView
    {
        $qb = $this->entityManager->getConnection()->createQueryBuilder();

        $qb->select('*')
            ->from('calendar_events', 'ce')
            ->where('ce.start_date >= :firstDay')
            ->andWhere('ce.end_date <= :lastDay')
            ->setParameter('lastDay', $end->format('Y-m-d H:i:s'))
            ->setParameter('firstDay', $start->format('Y-m-d H:i:s'))
        ;

        /** @var array<array{id: string, slug: string, index: int, name: string, short_name: ?string, start_date: string, end_date: string, series: string, venue: string, sessions: string}> $result */
        $result = $qb->executeQuery()->fetchAllAssociative();

        return JsonableCalendarEventsView::fromSource($result);
    }
}
