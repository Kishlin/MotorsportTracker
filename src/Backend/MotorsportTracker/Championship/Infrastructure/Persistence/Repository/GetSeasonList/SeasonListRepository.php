<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Repository\GetSeasonList;

use Kishlin\Backend\MotorsportTracker\Championship\Application\GetSeasonList\SeasonListDTO;
use Kishlin\Backend\MotorsportTracker\Championship\Application\GetSeasonList\SeasonListGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class SeasonListRepository extends CoreRepository implements SeasonListGateway
{
    public function find(StringValueObject $championshipName): SeasonListDTO
    {
        $qb = $this->connection->createQueryBuilder();

        $qb
            ->select('s.year', 'year')
            ->from('season', 's')
            ->innerJoin('series', 'c', $qb->expr()->eq('s.series', 'c.id'))
            ->andWhere($qb->expr()->eq('c.name', ':championship'))
            ->withParam('championship', $championshipName->value())
        ;

        /** @var array<array{year: int}> $ret */
        $ret = $this->connection->execute($qb->buildQuery())->fetchAllAssociative();

        return SeasonListDTO::forList(
            array_map(
                static function (array $data) { return $data['year']; },
                $ret,
            ),
        );
    }
}
