<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\SeasonId;
use Kishlin\Backend\MotorsportTracker\Event\Application\ViewCalendar\CalendarViewGateway;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\EventStep;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\View\JsonableCalendarView;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\SeasonRepositorySpy;

final class CalendarViewRepositorySpy implements CalendarViewGateway
{
    public function __construct(
        private EventRepositorySpy $eventRepositorySpy,
        private SeasonRepositorySpy $seasonRepositorySpy,
        private EventStepRepositorySpy $eventStepRepositorySpy,
    ) {
    }

    public function view(): JsonableCalendarView
    {
        $seasonRepository = $this->seasonRepositorySpy;
        $eventRepository  = $this->eventRepositorySpy;

        return JsonableCalendarView::fromSource(
            array_map(
                static function (EventStep $eventStep) use ($seasonRepository, $eventRepository) {
                    $event = $eventRepository->get(EventId::fromOther($eventStep->eventId()));
                    assert(null !== $event);

                    $season = $seasonRepository->get(SeasonId::fromOther($event->seasonId()));
                    assert(null !== $season);

                    return [
                        'date_time'    => $eventStep->dateTime()->value()->format('Y-m-d H:i:s'),
                        'championship' => $season->championshipId()->value(),
                        'venue'        => $event->venueId()->value(),
                        'type'         => $eventStep->typeId()->value(),
                        'event'        => $eventStep->eventId()->value(),
                    ];
                },
                $this->eventStepRepositorySpy->all(),
            )
        );
    }
}