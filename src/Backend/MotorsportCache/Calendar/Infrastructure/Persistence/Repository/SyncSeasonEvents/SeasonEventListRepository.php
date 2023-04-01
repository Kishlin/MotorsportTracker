<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\SyncSeasonEvents;

use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncSeasonEvents\Gateway\SeasonEventListGateway;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncSeasonEvents\SeasonEventListDTO;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;
use Kishlin\Backend\Tools\Helpers\StringHelper;

final class SeasonEventListRepository extends CoreRepository implements SeasonEventListGateway
{
    public function findEventsForSeason(StringValueObject $championship, StrictlyPositiveIntValueObject $year): SeasonEventListDTO
    {
        $qb = $this->connection->createQueryBuilder();

        $query = $qb
            ->select('e.id, e.name, e.index')
            ->from('event', 'e')
            ->innerJoin('season', 's', $qb->expr()->eq('e.season', 's.id'))
            ->innerJoin('championship', 'c', $qb->expr()->eq('s.championship', 'c.id'))
            ->where($qb->expr()->eq('c.name', ':championship'))
            ->withParam('championship', $championship->value())
            ->andWhere($qb->expr()->eq('s.year', ':year'))
            ->withParam('year', $year->value())
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
