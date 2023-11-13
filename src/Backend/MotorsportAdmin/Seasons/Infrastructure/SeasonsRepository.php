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
SELECT season.*,
       (
           SELECT count(*)
           FROM event
           WHERE event.season = season.id
       ) as events,
       (
           SELECT count(sc.id)
           FROM season s2
                    INNER JOIN standing_constructor sc on s2.id = sc.season
           WHERE s2.id = season.id
       ) + (
           SELECT count(sd.id)
           FROM season s2
                    INNER JOIN standing_driver sd on s2.id = sd.season
           WHERE s2.id = season.id
       ) + (
           SELECT count(st.id)
           FROM season s2
                    INNER JOIN standing_team st on s2.id = st.season
           WHERE s2.id = season.id
       ) as standings
FROM season
         LEFT JOIN series ON season.series = series.id
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
