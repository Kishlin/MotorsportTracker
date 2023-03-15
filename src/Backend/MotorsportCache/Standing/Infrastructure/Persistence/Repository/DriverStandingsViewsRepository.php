<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Infrastructure\Persistence\Repository;

use JsonException;
use Kishlin\Backend\MotorsportCache\Standing\Domain\Entity\DriverStandingsView;
use Kishlin\Backend\MotorsportCache\Standing\Domain\Gateway\DriverStandingsViewsGateway;
use Kishlin\Backend\Persistence\SQL\SQLQuery;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CacheRepository;

final class DriverStandingsViewsRepository extends CacheRepository implements DriverStandingsViewsGateway
{
    public function save(DriverStandingsView $view): void
    {
        $this->persist($view);
    }

    public function deleteIfExists(string $championship, int $year): void
    {
        $this->connection->execute(SQLQuery::create(
            'DELETE FROM driver_standings_view WHERE championship_slug = :championship AND year = :year;',
            ['championship' => $championship, 'year' => $year],
        ));
    }

    /**
     * @throws JsonException
     */
    public function findOne(string $championshipSlug, int $year): ?DriverStandingsView
    {
        $qb = $this->connection->createQueryBuilder();

        $qb
            ->select('*')
            ->from('driver_standings_view', 'tsv')
            ->where($qb->expr()->eq('tsv.championship_slug', ':championship_slug'))
            ->withParam('championship_slug', $championshipSlug)
            ->andWhere($qb->expr()->eq('tsv.year', ':year'))
            ->withParam('year', $year)
            ->limit(1)
        ;

        /** @var array<array{id: string, championship_slug: string, year: int, events: string, standings: string}> $result */
        $result = $this->connection->execute($qb->buildQuery())->fetchAllAssociative();

        if (0 === count($result)) {
            return null;
        }

        return DriverStandingsView::fromData($result[0]);
    }
}
