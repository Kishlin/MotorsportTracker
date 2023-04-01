<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\ViewSeasonEvents;

use JsonException;
use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewSeasonEvents\SeasonEventsJsonableView;
use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewSeasonEvents\SeasonEventsNotFoundException;
use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewSeasonEvents\ViewSeasonEventsGateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CacheRepository;

final class ViewSeasonEventsRepository extends CacheRepository implements ViewSeasonEventsGateway
{
    /**
     * @throws JsonException
     */
    public function viewForSeason(string $championshipSlug, int $year): SeasonEventsJsonableView
    {
        $qb = $this->connection->createQueryBuilder();

        $query = $qb
            ->select('events')
            ->from('season_events')
            ->where($qb->expr()->eq('championship', ':championship'))
            ->withParam('championship', $championshipSlug)
            ->where($qb->expr()->eq('year', ':year'))
            ->withParam('year', $year)
            ->limit(1)
            ->buildQuery()
        ;

        /** @var null|string $result */
        $result = $this->connection->execute($query)->fetchOne();

        if (null === $result) {
            throw new SeasonEventsNotFoundException();
        }

        return SeasonEventsJsonableView::fromSource($result);
    }
}
