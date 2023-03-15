<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\SyncCalendarEvents;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway\CalendarEventEntry;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway\FindEventsGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class FindEventsRepositoryUsingDoctrine extends CoreRepository implements FindEventsGateway
{
    private const QUERY = <<<'SQL'
SELECT
    json_build_object(
        'name', v.name,
        'slug', v.slug,
        'country', json_build_object(
            'code', c.code,
            'name', c.name
        )
    ) as venue,
    e.index as index,
    e.slug as slug,
    e.name as name,
    e.short_name as short_name,
    e.start_date as start_date,
    e.end_date as end_date,
    (
        CASE
            WHEN 0 = count(es.slug)
            THEN json_build_object()
            ELSE
                json_agg(
                    json_build_object(
                        'type', t.label,
                        'slug', es.slug,
                        'has_result', es.has_result,
                        'start_date', es.start_date,
                        'end_date', es.end_date
                    )
                )
        END
    ) as sessions
FROM events e
LEFT JOIN event_sessions es on es.event = e.id
LEFT JOIN session_types t on t.id = es.type
LEFT JOIN venues v on e.venue = v.id
LEFT JOIN countries c on v.country = c.id
LEFT JOIN seasons s on e.season = s.id
LEFT JOIN championships cs on cs.id = s.championship
WHERE s.year = :year
AND cs.slug = :slug
GROUP BY e.id, v.name, v.slug, c.name, c.code
SQL;

    /**
     * @throws Exception
     */
    public function findAll(StringValueObject $seriesSlug, PositiveIntValueObject $year): array
    {
        $params = ['slug' => $seriesSlug->value(), 'year' => $year->value()];

        /**
         * @var array{
         *     venue: string,
         *     index: int,
         *     slug: string,
         *     name: string,
         *     short_name: ?string,
         *     start_date: ?string,
         *     end_date: ?string,
         *     sessions: string
         * }[] $data
         */
        $data = $this->entityManager->getConnection()->executeQuery(self::QUERY, $params)->fetchAllAssociative();

        return array_map(
            static function ($eventData): CalendarEventEntry {
                /** @var array{name: string, slug: string, country: array{code: string, name: string}} $venue */
                $venue = json_decode($eventData['venue'], true);

                /** @var array{type: string, slug: string, has_result: bool, start_date: ?string, end_date: ?string}[] $sessions */
                $sessions = json_decode($eventData['sessions'], true);

                $eventData['venue']    = $venue;
                $eventData['sessions'] = $sessions;

                return CalendarEventEntry::fromData($eventData);
            },
            $data,
        );
    }
}
