<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\DriverStandingsView;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\Gateway\DriverStandingsViewsGateway;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\StandingsViewChampionshipSlug;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\StandingsViewYear;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class DriverStandingsViewsRepositoryUsingDoctrine extends DoctrineRepository implements DriverStandingsViewsGateway
{
    public function save(DriverStandingsView $view): void
    {
        $this->persist($view);
    }

    public function findOne(string $championshipSlug, int $year): DriverStandingsView
    {
        $criteria = [
            'championshipSlug' => new StandingsViewChampionshipSlug($championshipSlug),
            'year'             => new StandingsViewYear($year),
        ];

        $view = $this->entityManager->getRepository(DriverStandingsView::class)->findOneBy($criteria);

        assert($view instanceof DriverStandingsView);

        return $view;
    }
}
