<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Calendar;

use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway\CalendarEventUpsert;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway\SaveCalendarEventGateway;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\Entity\CalendarEvent;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property CalendarEvent[] $objects
 *
 * @method CalendarEvent[]    all()
 * @method null|CalendarEvent get(UuidValueObject $id)
 * @method CalendarEvent      safeGet(UuidValueObject $id)
 */
final class CalendarEventRepositorySpy extends AbstractRepositorySpy implements SaveCalendarEventGateway
{
    public function save(CalendarEvent $event): CalendarEventUpsert
    {
        $id  = $event->id()->value();
        $ret = array_key_exists($id, $this->objects) ? CalendarEventUpsert::UPDATED : CalendarEventUpsert::CREATED;

        $this->objects[$id] = $event;

        return $ret;
    }
}
