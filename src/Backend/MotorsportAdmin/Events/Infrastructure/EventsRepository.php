<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportAdmin\Events\Infrastructure;

use Kishlin\Backend\MotorsportAdmin\Events\Application\ListEvents\EventsGateway;
use Kishlin\Backend\Persistence\SQL\SQLQuery;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepositoryInterface;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\ReadRepository;

final readonly class EventsRepository extends ReadRepository implements CoreRepositoryInterface, EventsGateway
{
    private const QUERY = <<<'SQL'
SELECT
    event.id,
    event.name,
    count(DISTINCT session.id) as sessions,
    COUNT(DISTINCT CASE WHEN session.has_result THEN session.id END) AS sessions_with_results,
    COUNT(DISTINCT CASE WHEN c.id IS NOT NULL THEN session.id END) AS sessions_with_classification,
    COUNT(DISTINCT CASE WHEN rl.id IS NOT NULL THEN session.id END) AS sessions_with_race_lap
FROM event
        INNER JOIN season ON event.season = season.id
        INNER JOIN series ON season.series = series.id
        INNER JOIN event_session session ON event.id = session.event
        LEFT JOIN entry ON session.id = entry.session
        LEFT JOIN classification c on entry.id = c.entry
        LEFT JOIN race_lap rl on entry.id = rl.entry
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
