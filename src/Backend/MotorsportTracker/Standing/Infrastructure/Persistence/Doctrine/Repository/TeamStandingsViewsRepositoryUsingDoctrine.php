<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\TeamStandingsView;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\Gateway\TeamStandingsViewsGateway;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\StandingsViewChampionshipSlug;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\StandingsViewYear;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class TeamStandingsViewsRepositoryUsingDoctrine extends DoctrineRepository implements TeamStandingsViewsGateway
{
    public function save(TeamStandingsView $view): void
    {
        $this->persist($view);
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
