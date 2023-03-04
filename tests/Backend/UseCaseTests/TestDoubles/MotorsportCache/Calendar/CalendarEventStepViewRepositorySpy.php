<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Calendar;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewCalendar\JsonableCalendarView;
use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewCalendar\ViewCalendarGateway;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\Entity\CalendarEventStepView;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property CalendarEventStepView[] $objects
 *
 * @method CalendarEventStepView[]    all()
 * @method null|CalendarEventStepView get(UuidValueObject $id)
 * @method CalendarEventStepView      safeGet(UuidValueObject $id)
 */
final class CalendarEventStepViewRepositorySpy extends AbstractRepositorySpy implements ViewCalendarGateway
{
    public function save(CalendarEventStepView $calendarEventStepView): void
    {
        $this->add($calendarEventStepView);
    }

    public function view(DateTimeImmutable $start, DateTimeImmutable $end): JsonableCalendarView
    {
        $events = array_filter(
            $this->objects,
            static function (CalendarEventStepView $view) use ($start, $end) {
                return $start <= $view->dateTime()->value() && $view->dateTime()->value() <= $end;
            },
        );

        $eventsAsArray = array_map(
            static function (CalendarEventStepView $view) {
                return [
                    'championship_slug' => $view->championshipSlug()->value(),
                    'color'             => $view->color()->value(),
                    'icon'              => $view->icon()->value(),
                    'name'              => $view->name()->value(),
                    'type'              => $view->type()->value(),
                    'venue_label'       => $view->venueLabel()->value(),
                    'date_time'         => $view->dateTime()->value()->format('Y-m-d H:i:s'),
                    'reference'         => $view->reference()->value(),
                ];
            },
            $events,
        );

        return JsonableCalendarView::fromSource($eventsAsArray);
    }
}
