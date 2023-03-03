<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\MotorsportTracker\Standing\Application\ViewDriverStandingsForSeason\DriverStandingsForSeasonGateway;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\View\JsonableStandingsView;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepository;

final class DriverStandingsForSeasonRepositoryUsingDoctrine extends CoreRepository implements DriverStandingsForSeasonGateway
{
    /**
     * @throws Exception
     */
    public function view(string $seasonId): JsonableStandingsView
    {
        $qb = $this->entityManager->getConnection()->createQueryBuilder();

        $qb->select('e.index as index, ds.driver as rankee, ds.points as points')
            ->from('driver_standings', 'ds')
            ->join('ds', 'events', 'e', 'ds.event = e.id')
            ->where('e.season = :seasonId')
            ->setParameter('seasonId', $seasonId)
        ;

        /** @var array{index: int, rankee: string, points: float}[] $result */
        $result = $qb->executeQuery()->fetchAllAssociative();

        return JsonableStandingsView::fromSource($result);
    }
}
