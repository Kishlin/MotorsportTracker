<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Doctrine\Repository\SyncCalendarEvents;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway\CalendarEventUpsert;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway\SaveCalendarEventGateway;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\Entity\CalendarEvent;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CacheRepository;

final class SaveCalendarEventRepositoryUsingDoctrine extends CacheRepository implements SaveCalendarEventGateway
{
    private const DELETE_QUERY = 'DELETE FROM calendar_events WHERE calendar_events.slug = :slug';

    /**
     * @throws Exception
     */
    public function save(CalendarEvent $event): CalendarEventUpsert
    {
        $params = ['slug' => $event->slug()->value()];
        $ret    = $this->entityManager->getConnection()->executeQuery(self::DELETE_QUERY, $params)->fetchAllAssociative();

        $this->persist($event);

        if (empty($ret)) {
            return CalendarEventUpsert::CREATED;
        }

        return CalendarEventUpsert::UPDATED;
    }
}
