<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportAdmin\Seasons\Infrastructure;

use Kishlin\Backend\MotorsportAdmin\Seasons\Application\ListSeasons\SeasonsGateway;
use Kishlin\Backend\Persistence\SQL\SQLQuery;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepositoryInterface;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\ReadRepository;

final readonly class SeasonsRepository extends ReadRepository implements CoreRepositoryInterface, SeasonsGateway
{
    private const QUERY = <<<'SQL'
SELECT season.*, count(event.id) as events, count(sc.id) + count(sd.id) + count(st.id) as standings
FROM season
LEFT JOIN series ON season.series = series.id
LEFT JOIN event ON event.season = season.id
LEFT JOIN standing_constructor sc on season.id = sc.season
LEFT JOIN standing_driver sd on season.id = sd.season
LEFT JOIN standing_team st on season.id = st.season
WHERE series.name = :seriesName
GROUP BY season.id, season.year
ORDER BY season.year DESC
SQL;

    public function find(string $seriesName): array
    {
        $query = SQLQuery::create(self::QUERY, ['seriesName' => $seriesName]);

        return $this->connection->execute($query)->fetchAllAssociative();
    }
}
