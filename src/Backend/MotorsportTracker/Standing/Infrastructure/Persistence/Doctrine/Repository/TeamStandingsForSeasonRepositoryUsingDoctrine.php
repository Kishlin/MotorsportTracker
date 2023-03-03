<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\MotorsportTracker\Standing\Application\ViewTeamStandingsForSeason\TeamStandingsForSeasonGateway;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\View\JsonableStandingsView;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepository;

final class TeamStandingsForSeasonRepositoryUsingDoctrine extends CoreRepository implements TeamStandingsForSeasonGateway
{
    /**
     * @throws Exception
     */
    public function view(string $seasonId): JsonableStandingsView
    {
        $qb = $this->entityManager->getConnection()->createQueryBuilder();

        $qb->select('e.index as index, ts.team as rankee, ts.points as points')
            ->from('team_standings', 'ts')
            ->join('ts', 'events', 'e', 'ts.event = e.id')
            ->where('e.season = :seasonId')
            ->setParameter('seasonId', $seasonId)
        ;

        /** @var array{index: int, rankee: string, points: float}[] $result */
        $result = $qb->executeQuery()->fetchAllAssociative();

        return JsonableStandingsView::fromSource($result);
    }
}
