<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Gateway\ScrapSeasons;

use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapSeasons\SeriesDTO;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapSeasons\SeriesGateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class SeriesRepository extends CoreRepository implements SeriesGateway
{
    public function findMotorsportStatsUuidForName(string $name): ?SeriesDTO
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select('c.id, c.ref')
            ->from('championship', 'c')
            ->where($qb->expr()->eq('c.name', ':name'))
            ->withParam('name', $name)
            ->limit(1)
        ;

        /** @var array{id: string, ref: string}|array{} $result */
        $result = $this->connection->execute($qb->buildQuery())->fetchAssociative();

        if (empty($result)) {
            return null;
        }

        return SeriesDTO::fromScalars($result);
    }
}
