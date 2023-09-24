<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Schedule\Infrastructure\Repository\UpdateSeasonScheduleCache;

use Kishlin\Backend\MotorsportCache\Schedule\Application\UpdateSeasonScheduleCache\SeasonEventListDTO;
use Kishlin\Backend\MotorsportCache\Schedule\Application\UpdateSeasonScheduleCache\SeasonScheduleDataGateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;
use Kishlin\Backend\Tools\Helpers\StringHelper;

final class SeasonScheduleDataRepository extends CoreRepository implements SeasonScheduleDataGateway
{
    public function findEventsForSeason(string $championship, int $year): SeasonEventListDTO
    {
        $qb = $this->connection->createQueryBuilder();

        $query = $qb
            ->select('e.id, e.name, e.index')
            ->from('event', 'e')
            ->innerJoin('season', 's', $qb->expr()->eq('e.season', 's.id'))
            ->innerJoin('series', 'c', $qb->expr()->eq('s.series', 'c.id'))
            ->where($qb->expr()->eq('c.name', ':championship'))
            ->withParam('championship', $championship)
            ->andWhere($qb->expr()->eq('s.year', ':year'))
            ->withParam('year', $year)
            ->orderBy('e.index')
            ->buildQuery()
        ;

        /** @var array{id: string, name: string, index:int}[] $result */
        $result = $this->connection->execute($query)->fetchAllAssociative();

        $events = [];

        foreach ($result as $data) {
            $slug = StringHelper::slugify($data['name']);

            $events[$slug] = [
                'id'    => $data['id'],
                'slug'  => $slug,
                'name'  => $data['name'],
                'index' => $data['index'],
            ];
        }

        return SeasonEventListDTO::fromData($events);
    }
}
