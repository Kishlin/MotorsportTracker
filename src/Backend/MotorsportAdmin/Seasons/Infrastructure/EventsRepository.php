<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportAdmin\Seasons\Infrastructure;

use Kishlin\Backend\MotorsportAdmin\Seasons\Application\ListEvents\EventsGateway;
use Kishlin\Backend\Persistence\SQL\SQLQuery;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepositoryInterface;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\ReadRepository;

final readonly class EventsRepository extends ReadRepository implements CoreRepositoryInterface, EventsGateway
{
    private const QUERY = <<<'SQL'
SELECT
    event.id,
    event.name,
    count(session.id) as sessions
FROM event
    INNER JOIN season ON event.season = season.id
    INNER JOIN series ON season.series = series.id
    LEFT JOIN event_session session ON event.id = session.event
WHERE series.name = :seriesName
AND season.year = :year
GROUP BY event.id, event.name, event.index
ORDER BY event.index
SQL;

    public function find(string $seriesName, int $year): array
    {
        $query = SQLQuery::create(self::QUERY, ['seriesName' => $seriesName, 'year' => $year]);

        return $this->connection->execute($query)->fetchAllAssociative();
    }
}
