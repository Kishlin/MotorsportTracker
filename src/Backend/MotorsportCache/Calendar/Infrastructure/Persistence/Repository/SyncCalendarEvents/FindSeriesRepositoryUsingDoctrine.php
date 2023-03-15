<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\SyncCalendarEvents;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway\FindSeriesGateway;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\CalendarEventSeries;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class FindSeriesRepositoryUsingDoctrine extends CoreRepository implements FindSeriesGateway
{
    /**
     * @throws Exception
     */
    public function findForSlug(StringValueObject $seriesCode, PositiveIntValueObject $year): ?CalendarEventSeries
    {
        $qb = $this->entityManager->getConnection()->createQueryBuilder();

        $qb
            ->select('c.name as name, c.slug as slug, s.year as year, cp.icon as icon, cp.color as color')
            ->from('seasons', 's')
            ->leftJoin('s', 'championships', 'c', 'c.id = s.championship')
            ->leftJoin('c', 'championship_presentations', 'cp', 'c.id = cp.championship')
            ->where('c.slug = :seriesSlug')
            ->andWhere('s.year = :year')
            ->setParameter('seriesSlug', $seriesCode->value())
            ->setParameter('year', $year->value())
        ;

        /** @var array{name: string, slug: string, year: int, icon: string, color: string}|false $data */
        $data = $qb->executeQuery()->fetchAssociative();

        if (false === $data) {
            return null;
        }

        return CalendarEventSeries::fromData($data);
    }
}
