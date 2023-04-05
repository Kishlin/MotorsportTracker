<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Event\Infrastructure\Repository\ViewCachedEvents;

use Kishlin\Backend\MotorsportCache\Event\Application\ViewCachedEvents\CachedEventsJsonableView;
use Kishlin\Backend\MotorsportCache\Event\Application\ViewCachedEvents\ViewCachedEventsGateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CacheRepository;

final class ViewCachedEventsRepository extends CacheRepository implements ViewCachedEventsGateway
{
    public function findAll(): CachedEventsJsonableView
    {
        $qb = $this->connection->createQueryBuilder();

        $query = $qb
            ->select('championship, year, event')
            ->from('event_cached')
            ->buildQuery()
        ;

        /** @var array<array{championship: string, year: int, event: string}> $result */
        $result = $this->connection->execute($query)->fetchAllAssociative();

        return CachedEventsJsonableView::fromData($result);
    }
}
