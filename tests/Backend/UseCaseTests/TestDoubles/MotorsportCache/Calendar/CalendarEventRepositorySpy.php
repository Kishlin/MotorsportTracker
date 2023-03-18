<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Calendar;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway\CalendarEventUpsert;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway\SaveCalendarEventGateway;
use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewCalendarEvents\JsonableCalendarEventsView;
use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewCalendarEvents\ViewCalendarEventsGateway;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\Entity\CalendarEvent;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;
use function PHPUnit\Framework\assertNotFalse;

/**
 * @property CalendarEvent[] $objects
 *
 * @method CalendarEvent[]    all()
 * @method null|CalendarEvent get(UuidValueObject $id)
 * @method CalendarEvent      safeGet(UuidValueObject $id)
 */
final class CalendarEventRepositorySpy extends AbstractRepositorySpy implements SaveCalendarEventGateway, ViewCalendarEventsGateway
{
    public function save(CalendarEvent $event): CalendarEventUpsert
    {
        $id  = $event->id()->value();
        $ret = array_key_exists($id, $this->objects) ? CalendarEventUpsert::UPDATED : CalendarEventUpsert::CREATED;

        $this->objects[$id] = $event;

        return $ret;
    }

    public function view(DateTimeImmutable $start, DateTimeImmutable $end): JsonableCalendarEventsView
    {
        return JsonableCalendarEventsView::fromSource(
            array_map(
                static function (CalendarEvent $calendarEvent) {
                    $startDate = null !== $calendarEvent->startDate()->value() ?
                        $calendarEvent->startDate()->value()->format('Y-m-d H:i:s') :
                        'null'
                    ;

                    $endDate = null !== $calendarEvent->endDate()->value() ?
                        $calendarEvent->endDate()->value()->format('Y-m-d H:i:s') :
                        'null'
                    ;

                    $venue   = json_encode($calendarEvent->venue()->data());
                    $session = json_encode($calendarEvent->sessions()->data());

                    assertNotFalse($session);
                    assertNotFalse($venue);

                    return [
                        'series'     => serialize($calendarEvent->series()->data()),
                        'venue'      => $venue,
                        'id'         => $calendarEvent->id()->value(),
                        'reference'  => $calendarEvent->reference()->value(),
                        'slug'       => $calendarEvent->slug()->value(),
                        'index'      => $calendarEvent->index()->value(),
                        'name'       => $calendarEvent->name()->value(),
                        'short_name' => $calendarEvent->shortName()->value(),
                        'short_code' => $calendarEvent->shortCode()->value(),
                        'start_date' => $startDate,
                        'end_date'   => $endDate,
                        'sessions'   => $session,
                    ];
                },
                array_filter(
                    $this->objects,
                    static function (CalendarEvent $calendarEvent) use ($start, $end) {
                        return (null !== $calendarEvent->startDate()->value())
                            && ($calendarEvent->startDate()->value() > $start)
                            && (
                                (
                                    null === $calendarEvent->endDate()->value()
                                    && $calendarEvent->startDate()->value() < $end
                                )
                                || $calendarEvent->endDate()->value() < $end
                            )
                        ;
                    }
                ),
            ),
        );
    }
}
