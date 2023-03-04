<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\MotorsportCache\Standing\Domain\Entity\DriverStandingsView;
use Kishlin\Backend\MotorsportCache\Standing\Domain\Gateway\DriverStandingsViewsGateway;
use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\StandingsViewChampionshipSlug;
use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\StandingsViewYear;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CacheRepository;

final class DriverStandingsViewsRepositoryUsingDoctrine extends CacheRepository implements DriverStandingsViewsGateway
{
    public function save(DriverStandingsView $view): void
    {
        $this->persist($view);
    }

    /**
     * @throws Exception
     */
    public function deleteIfExists(string $championship, int $year): void
    {
        $this->entityManager->getConnection()->executeQuery(
            "DELETE FROM driver_standings_views WHERE championship_slug = '{$championship}' AND year = {$year};",
        );
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
