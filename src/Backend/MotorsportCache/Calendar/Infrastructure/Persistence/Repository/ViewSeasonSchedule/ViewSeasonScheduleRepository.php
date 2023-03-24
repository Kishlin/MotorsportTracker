<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\ViewSeasonSchedule;

use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewSeasonSchedule\ViewSeasonScheduleGateway;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\View\JsonableEventsView;
use Kishlin\Backend\Persistence\Core\QueryBuilder\OrderBy;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CacheRepository;
use Kishlin\Backend\Tools\Helpers\StringHelper;

final class ViewSeasonScheduleRepository extends CacheRepository implements ViewSeasonScheduleGateway
{
    public function viewSchedule(string $championship, int $year): JsonableEventsView
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select('*')
            ->from('calendar_event', 'ce')
            ->where('ce.slug LIKE :slug')
            ->orderBy('ce.index', OrderBy::ASC)
            ->withParam('slug', StringHelper::slugify($championship, (string) $year) . '%')
        ;

        /** @var array<array{id: string, reference: string, slug: string, index: int, name: string, short_name: ?string, short_code: ?string, start_date: string, end_date: string, series: string, venue: string, sessions: string}> $result */
        $result = $this->connection->execute($qb->buildQuery())->fetchAllAssociative();

        return JsonableEventsView::fromSource($result);
    }
}
