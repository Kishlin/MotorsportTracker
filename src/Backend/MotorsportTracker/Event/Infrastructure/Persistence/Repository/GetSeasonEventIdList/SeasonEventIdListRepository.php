<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Repository\GetSeasonEventIdList;

use Kishlin\Backend\MotorsportTracker\Event\Application\GetSeasonEventIdList\SeasonEventIdListDTO;
use Kishlin\Backend\MotorsportTracker\Event\Application\GetSeasonEventIdList\SeasonEventIdListGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class SeasonEventIdListRepository extends CoreRepository implements SeasonEventIdListGateway
{
    public function find(
        StringValueObject $championshipName,
        PositiveIntValueObject $year,
        NullableStringValueObject $eventFilter,
    ): SeasonEventIdListDTO {
        $qb = $this->connection->createQueryBuilder();

        $qb
            ->select('e.id')
            ->from('event', 'e')
            ->innerJoin('season', 's', $qb->expr()->eq('e.season', 's.id'))
            ->innerJoin('championship', 'c', $qb->expr()->eq('s.championship', 'c.id'))
            ->andWhere($qb->expr()->eq('c.name', ':championship'))
            ->andWhere($qb->expr()->eq('s.year', ':year'))
            ->withParam('championship', $championshipName->value())
            ->withParam('year', $year->value())
        ;

        if (null !== $eventFilter->value()) {
            $qb
                ->andWhere($qb->expr()->like('e.name', ':eventFilter'))
                ->withParam('eventFilter', "%{$eventFilter->value()}%")
            ;
        }

        /** @var array<array{id: string}> $ret */
        $ret = $this->connection->execute($qb->buildQuery())->fetchAllAssociative();

        return SeasonEventIdListDTO::forList(
            array_map(
                static function (array $data) { return $data['id']; },
                $ret,
            ),
        );
    }
}
