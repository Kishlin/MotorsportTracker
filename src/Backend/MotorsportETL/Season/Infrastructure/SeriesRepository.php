<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Season\Infrastructure;

use Kishlin\Backend\MotorsportETL\Season\Application\ScrapSeasons\SeriesDTO;
use Kishlin\Backend\MotorsportETL\Season\Application\ScrapSeasons\SeriesGateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepositoryInterface;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\ReadRepository;

final readonly class SeriesRepository extends ReadRepository implements SeriesGateway, CoreRepositoryInterface
{
    public function find(string $seriesName): ?SeriesDTO
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

        return SeriesDTO::forScalars($ret['id'], $ret['ref']);
    }
}
