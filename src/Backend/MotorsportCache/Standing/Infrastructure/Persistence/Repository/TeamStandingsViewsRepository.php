<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Infrastructure\Persistence\Repository;

use JsonException;
use Kishlin\Backend\MotorsportCache\Standing\Domain\Entity\TeamStandingsView;
use Kishlin\Backend\MotorsportCache\Standing\Domain\Gateway\TeamStandingsViewsGateway;
use Kishlin\Backend\Persistence\SQL\SQLQuery;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CacheRepository;

final class TeamStandingsViewsRepository extends CacheRepository implements TeamStandingsViewsGateway
{
    public function save(TeamStandingsView $view): void
    {
        $this->persist($view);
    }

    public function deleteIfExists(string $championship, int $year): void
    {
        $this->connection->execute(SQLQuery::create(
            'DELETE FROM team_standings_view WHERE championship_slug = :championship AND year = :year;',
            ['championship' => $championship, 'year' => $year],
        ));
    }

    /**
     * @throws JsonException
     */
    public function findOne(string $championshipSlug, int $year): ?TeamStandingsView
    {
        $qb = $this->connection->createQueryBuilder();

        $qb
            ->select('*')
            ->from('team_standings_view', 'tsv')
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

        return TeamStandingsView::fromData($result[0]);
    }
}
