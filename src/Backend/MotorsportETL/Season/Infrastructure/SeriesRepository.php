<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Season\Infrastructure;

use Kishlin\Backend\MotorsportETL\Season\Application\ScrapSeasons\SeriesGateway;
use Kishlin\Backend\MotorsportETL\Season\Domain\ValueObject\SeriesIdentity;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepositoryInterface;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\ReadRepository;

final readonly class SeriesRepository extends ReadRepository implements SeriesGateway, CoreRepositoryInterface
{
    public function find(string $seriesName): ?SeriesIdentity
    {
        $qb = $this->createQueryBuilder();

        $qb
            ->select('s.id')
            ->addSelect('s.ref')
            ->from('series', 's')
            ->where($qb->expr()->eq('s.name', ':seriesName'))
            ->withParam('seriesName', $seriesName)
            ->limit(1)
        ;

        /** @var null|array{id: string, ref: string} $ret */
        $ret = $this->connection->execute($qb->buildQuery())->fetchAssociative();

        if (empty($ret)) {
            return null;
        }

        return SeriesIdentity::forScalars($ret['id'], $ret['ref']);
    }
}
