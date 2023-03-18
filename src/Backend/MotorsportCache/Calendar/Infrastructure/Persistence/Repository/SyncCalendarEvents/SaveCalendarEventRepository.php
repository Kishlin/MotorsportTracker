<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\SyncCalendarEvents;

use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway\CalendarEventUpsert;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway\SaveCalendarEventGateway;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\Entity\CalendarEvent;
use Kishlin\Backend\Persistence\SQL\SQLQuery;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CacheRepository;

final class SaveCalendarEventRepository extends CacheRepository implements SaveCalendarEventGateway
{
    private const DELETE_QUERY = 'DELETE FROM calendar_event WHERE calendar_event.reference = :reference';

    public function save(CalendarEvent $event): CalendarEventUpsert
    {
        $params = ['reference' => $event->reference()->value()];
        $ret    = $this->connection->execute(SQLQuery::create(self::DELETE_QUERY, $params))->fetchAllAssociative();

        $this->persist($event);

        if (false === array_key_exists(0, $ret)) {
            return CalendarEventUpsert::CREATED;
        }

        return CalendarEventUpsert::UPDATED;
    }
}
