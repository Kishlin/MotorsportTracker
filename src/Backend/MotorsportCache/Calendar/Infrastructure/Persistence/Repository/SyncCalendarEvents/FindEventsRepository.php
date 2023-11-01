<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\SyncCalendarEvents;

use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway\CalendarEventEntry;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway\FindEventsGateway;
use Kishlin\Backend\Persistence\SQL\SQLQuery;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;
use Kishlin\Backend\Tools\Helpers\StringHelper;

final class FindEventsRepository extends CoreRepository implements FindEventsGateway
{
    private const QUERY = <<<'SQL'
SELECT
    json_build_object(
        'id', v.id,
        'name', v.name,
        'slug', v.name,
        'country', json_build_object(
            'id', c.id,
            'code', c.code,
            'name', c.name
        )
    ) as venue,
    e.id as reference,
    e.index as index,
    e.name as name,
    CONCAT(cs.name, '_', s.year, '_', e.index, '_', e.name) as slug,
    e.short_code as short_code,
    e.short_name as short_name,
    e.status as status,
    e.start_date as start_date,
    e.end_date as end_date,
    (
        CASE
            WHEN 0 = count(es.id)
            THEN json_build_object()
            ELSE
                json_agg(
                    json_build_object(
                        'id', es.id,
                        'type', t.label,
                        'slug', CONCAT(cs.name, '_', s.year, '_', e.name, '_', t.label),
                        'has_result', es.has_result,
                        'start_date', es.start_date,
                        'end_date', es.end_date
                    )
                )
        END
    ) as sessions
FROM event e
LEFT JOIN event_session es on es.event = e.id
LEFT JOIN session_type t on t.id = es.type
LEFT JOIN venue v on e.venue = v.id
LEFT JOIN country c on v.country = c.id
LEFT JOIN season s on e.season = s.id
LEFT JOIN series cs on cs.id = s.series
WHERE s.year = :year
AND cs.name = :championship
GROUP BY e.id, v.id, v.name, c.id, c.name, c.code, cs.name, s.year
SQL;

    public function findAll(StringValueObject $championship, PositiveIntValueObject $year): array
    {
        $params = ['championship' => $championship->value(), 'year' => $year->value()];
        $query  = SQLQuery::create(self::QUERY, $params);

        /**
         * @var array{
         *     venue: string,
         *     reference: string,
         *     index: int,
         *     name: string,
         *     slug: string,
         *     short_name: ?string,
         *     short_code: ?string,
         *     status: ?string,
         *     start_date: ?string,
         *     end_date: ?string,
         *     sessions: string
         * }[] $data
         */
        $data = $this->connection->execute($query)->fetchAllAssociative();

        return array_map(
            static function ($eventData): CalendarEventEntry {
                /** @var array{id: string, name: string, slug: string, country: array{id: string, code: string, name: string}} $venue */
                $venue         = json_decode($eventData['venue'], true);
                $venue['slug'] = StringHelper::slugify($venue['slug']);

                /** @var array{id: string, type: string, slug: string, has_result: bool, start_date: ?string, end_date: ?string}[] $rawSessions */
                $rawSessions = json_decode($eventData['sessions'], true);

                $eventData['slug'] = StringHelper::slugify($eventData['slug']);

                $sessions = array_map(
                    static function (array $session) {
                        $session['slug'] = StringHelper::slugify($session['slug']);

                        return $session;
                    },
                    $rawSessions,
                );

                $eventData['venue']    = $venue;
                $eventData['sessions'] = $sessions;

                return CalendarEventEntry::fromData($eventData);
            },
            $data,
        );
    }
}
