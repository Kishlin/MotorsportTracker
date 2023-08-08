<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\ViewCalendarEvents;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewCalendarEvents\ViewCalendarEventsGateway;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\View\JsonableEventsView;
use Kishlin\Backend\Persistence\Core\QueryBuilder\OrderBy;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CacheRepository;

final class ViewCalendarEventsRepository extends CacheRepository implements ViewCalendarEventsGateway
{
    public function view(DateTimeImmutable $start, DateTimeImmutable $end): JsonableEventsView
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select('*')
            ->from('calendar_event', 'ce')
            ->where($qb->expr()->gte('ce.start_date', ':firstDay'))
            ->andWhere($qb->expr()->lte('ce.end_date', ':lastDay'))
            ->orderBy('ce.start_date', OrderBy::ASC)
            ->withParam('lastDay', $end->format('Y-m-d H:i:s'))
            ->withParam('firstDay', $start->format('Y-m-d H:i:s'))
        ;

        /** @var array<array{id: string, reference: string, slug: string, index: int, name: string, short_name: ?string, short_code: ?string, status: ?string, start_date: string, end_date: string, series: string, venue: string, sessions: string}> $result */
        $result = $this->connection->execute($qb->buildQuery())->fetchAllAssociative();

        return JsonableEventsView::fromSource($result);
    }
}
