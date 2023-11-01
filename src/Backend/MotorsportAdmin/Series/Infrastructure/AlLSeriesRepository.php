<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportAdmin\Series\Infrastructure;

use Kishlin\Backend\MotorsportAdmin\Series\Application\ListSeries\AllSeriesGateway;
use Kishlin\Backend\Persistence\SQL\SQLQuery;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepositoryInterface;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\ReadRepository;

final readonly class AlLSeriesRepository extends ReadRepository implements CoreRepositoryInterface, AllSeriesGateway
{
    private const QUERY = <<<'SQL'
SELECT series.*, count(season.id) as seasons
FROM series
LEFT JOIN season ON season.series = series.id
GROUP BY series.id, series.name
ORDER BY series.name
SQL;

    public function all(): array
    {
        return $this->connection->execute(SQLQuery::create(self::QUERY))->fetchAllAssociative();
    }
}
