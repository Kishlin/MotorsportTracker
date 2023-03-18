<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\SyncCalendarEvents;

use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway\FindSeriesGateway;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventSeries;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class FindSeriesRepository extends CoreRepository implements FindSeriesGateway
{
    public function findForChampionship(StringValueObject $seriesCode, PositiveIntValueObject $year): ?CalendarEventSeries
    {
        $qb = $this->connection->createQueryBuilder();

        $qb
            ->select('c.name as name, s.year as year, cp.icon as icon, cp.color as color')
            ->from('season', 's')
            ->leftJoin('championship', 'c', $qb->expr()->eq('c.id', 's.championship'))
            ->leftJoin('championship_presentation', 'cp', $qb->expr()->eq('c.id', 'cp.championship'))
            ->where($qb->expr()->eq('c.name', ':seriesSlug'))
            ->andWhere($qb->expr()->eq('s.year', ':year'))
            ->withParam('seriesSlug', $seriesCode->value())
            ->withParam('year', $year->value())
        ;

        /** @var array{name: string, year: int, icon: string, color: string}|false $data */
        $data = $this->connection->execute($qb->buildQuery())->fetchAssociative();

        if (empty($data)) {
            return null;
        }

        return CalendarEventSeries::fromData($data);
    }
}
