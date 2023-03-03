<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\TeamStandingsView;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\Gateway\TeamStandingsViewsGateway;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\StandingsViewChampionshipSlug;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\StandingsViewYear;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CacheRepository;

final class TeamStandingsViewsRepositoryUsingDoctrine extends CacheRepository implements TeamStandingsViewsGateway
{
    public function save(TeamStandingsView $view): void
    {
        $this->persist($view);
    }

    /**
     * @throws Exception
     */
    public function deleteIfExists(string $championship, int $year): void
    {
        $this->entityManager->getConnection()->executeQuery(
            "DELETE FROM team_standings_views WHERE championship_slug = '{$championship}' AND year = {$year};",
        );
    }

    public function findOne(string $championshipSlug, int $year): TeamStandingsView
    {
        $criteria = [
            'championshipSlug' => new StandingsViewChampionshipSlug($championshipSlug),
            'year'             => new StandingsViewYear($year),
        ];

        $view = $this->entityManager->getRepository(TeamStandingsView::class)->findOneBy($criteria);

        assert($view instanceof TeamStandingsView);

        return $view;
    }
}
