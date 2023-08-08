<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Calendar;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway\CalendarEventUpsert;
use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\Gateway\SaveCalendarEventGateway;
use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewCalendarEvents\ViewCalendarEventsGateway;
use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewSeasonSchedule\ViewSeasonScheduleGateway;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\Entity\CalendarEvent;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\View\JsonableEventsView;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Tools\Helpers\StringHelper;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property CalendarEvent[] $objects
 *
 * @method CalendarEvent[]    all()
 * @method null|CalendarEvent get(UuidValueObject $id)
 * @method CalendarEvent      safeGet(UuidValueObject $id)
 */
final class CalendarEventRepositorySpy extends AbstractRepositorySpy implements SaveCalendarEventGateway, ViewCalendarEventsGateway, ViewSeasonScheduleGateway
{
    public function save(CalendarEvent $event): CalendarEventUpsert
    {
        $id  = $event->id()->value();
        $ret = array_key_exists($id, $this->objects) ? CalendarEventUpsert::UPDATED : CalendarEventUpsert::CREATED;

        $this->objects[$id] = $event;

        return $ret;
    }

    public function view(DateTimeImmutable $start, DateTimeImmutable $end): JsonableEventsView
    {
        return JsonableEventsView::fromSource(
            array_map(
                [$this, 'mapCalendarEventToData'],
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
                            );
                    }
                ),
            ),
        );
    }

    public function viewSchedule(string $championship, int $year): JsonableEventsView
    {
        return JsonableEventsView::fromSource(
            array_map(
                [$this, 'mapCalendarEventToData'],
                array_filter(
                    $this->objects,
                    static function (CalendarEvent $calendarEvent) use ($championship, $year) {
                        return str_starts_with($calendarEvent->slug()->value(), StringHelper::slugify($championship, (string) $year));
                    }
                ),
            ),
        );
    }

    /**
     * @return array{
     *     id: string,
     *     reference: string,
     *     slug: string,
     *     index: int,
     *     name: string,
     *     short_name: ?string,
     *     short_code: ?string,
     *     status: ?string,
     *     start_date: string,
     *     end_date: string,
     *     series: string,
     *     sessions: string,
     *     venue: string,
     * }
     */
    private static function mapCalendarEventToData(CalendarEvent $calendarEvent): array
    {
        $startDate = null !== $calendarEvent->startDate()->value() ?
            $calendarEvent->startDate()->value()->format('Y-m-d H:i:s') :
            'null';

        $endDate = null !== $calendarEvent->endDate()->value() ?
            $calendarEvent->endDate()->value()->format('Y-m-d H:i:s') :
            'null';

        $venue   = json_encode($calendarEvent->venue()->data());
        $session = json_encode($calendarEvent->sessions()->data());

        assert(false !== $session);
        assert(false !== $venue);

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
            'status'     => $calendarEvent->status()->value(),
            'start_date' => $startDate,
            'end_date'   => $endDate,
            'sessions'   => $session,
        ];
    }
}
