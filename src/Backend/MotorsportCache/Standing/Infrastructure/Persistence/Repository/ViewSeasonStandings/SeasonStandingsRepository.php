<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Infrastructure\Persistence\Repository\ViewSeasonStandings;

use JsonException;
use Kishlin\Backend\MotorsportCache\Standing\Application\ViewSeasonStandings\SeasonStandingsGateway;
use Kishlin\Backend\MotorsportCache\Standing\Application\ViewSeasonStandings\SeasonStandingsJsonableView;
use Kishlin\Backend\MotorsportCache\Standing\Application\ViewSeasonStandings\SeasonStandingsNotFoundException;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CacheRepository;

final class SeasonStandingsRepository extends CacheRepository implements SeasonStandingsGateway
{
    /**
     * @throws JsonException
     */
    public function viewForSeason(string $championshipSlug, int $year): SeasonStandingsJsonableView
    {
        $qb = $this->connection->createQueryBuilder();

        $query = $qb
            ->select('constructor')
            ->addSelect('team')
            ->addSelect('driver')
            ->from('standings')
            ->where($qb->expr()->eq('championship', ':championship'))
            ->withParam('championship', $championshipSlug)
            ->where($qb->expr()->eq('year', ':year'))
            ->withParam('year', $year)
            ->limit(1)
            ->buildQuery()
        ;

        /** @var null|array{constructor: string, team: string, driver: string} $result */
        $result = $this->connection->execute($query)->fetchAssociative();

        if (empty($result)) {
            throw new SeasonStandingsNotFoundException();
        }

        return SeasonStandingsJsonableView::fromSource($result);
    }
}
