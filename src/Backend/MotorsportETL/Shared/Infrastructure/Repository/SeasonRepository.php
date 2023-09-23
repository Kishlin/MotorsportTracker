<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Infrastructure\Repository;

use Kishlin\Backend\MotorsportETL\Shared\Domain\SeasonGateway;
use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\SeasonFilter;
use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\SeasonIdentity;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepositoryInterface;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\ReadRepository;

final readonly class SeasonRepository extends ReadRepository implements CoreRepositoryInterface, SeasonGateway
{
    public function find(SeasonFilter $filter): ?SeasonIdentity
    {
        $qb = $this->createQueryBuilder();

        $qb
            ->select('s.id')
            ->addSelect('s.ref')
            ->from('season', 's')
            ->innerJoin('series', 'se', $qb->expr()->eq('s.series', 'se.id'))
            ->where($qb->expr()->eq('se.name', ':seriesName'))
            ->andWhere($qb->expr()->eq('s.year', ':year'))
            ->withParam('seriesName', $filter->seriesName())
            ->withParam('year', $filter->year())
            ->limit(1)
        ;

        $ret = $this->connection->execute($qb->buildQuery())->fetchAssociative();

        if (empty($ret)) {
            return null;
        }

        return SeasonIdentity::forScalars(
            (string) $ret['id'],
            (string) $ret['ref'],
        );
    }
}
