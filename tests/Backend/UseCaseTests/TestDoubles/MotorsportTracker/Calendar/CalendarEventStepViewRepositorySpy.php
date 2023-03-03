<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Calendar;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\CreateViewOnEventStepCreation\CalendarEventStepViewGateway;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation\CalendarViewsToUpdate;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation\CalendarViewsToUpdateGateway;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation\NewPresentationApplierGateway;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation\PresentationToApply;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\ViewCalendar\JsonableCalendarView;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\ViewCalendar\ViewCalendarGateway;
use Kishlin\Backend\MotorsportTracker\Calendar\Domain\Entity\CalendarEventStepView;
use Kishlin\Backend\MotorsportTracker\Calendar\Domain\ValueObject\CalendarEventStepViewColor;
use Kishlin\Backend\MotorsportTracker\Calendar\Domain\ValueObject\CalendarEventStepViewIcon;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property CalendarEventStepView[] $objects
 *
 * @method CalendarEventStepView[]    all()
 * @method null|CalendarEventStepView get(UuidValueObject $id)
 * @method CalendarEventStepView      safeGet(UuidValueObject $id)
 */
final class CalendarEventStepViewRepositorySpy extends AbstractRepositorySpy implements CalendarViewsToUpdateGateway, CalendarEventStepViewGateway, NewPresentationApplierGateway, ViewCalendarGateway
{
    public function save(CalendarEventStepView $calendarEventStepView): void
    {
        $this->add($calendarEventStepView);
    }

    public function findForSlug(string $slug): CalendarViewsToUpdate
    {
        $idList = [];
        foreach ($this->all() as $calendarEventStepView) {
            if ($slug !== $calendarEventStepView->championshipSlug()->value()) {
                continue;
            }

            $idList[] = $calendarEventStepView->id()->value();
        }

        return CalendarViewsToUpdate::fromScalars($idList);
    }

    public function applyDataToViews(CalendarViewsToUpdate $viewsToUpdate, PresentationToApply $presentationToApply): void
    {
        $idsToUpdate = $viewsToUpdate->idList();
        $viewList    = $this->objects;

        foreach ($viewList as $view) {
            foreach ($idsToUpdate as $key => $id) {
                if ($id !== $view->id()->value()) {
                    continue;
                }

                $newView = CalendarEventStepView::instance(
                    clone $view->id(),
                    clone $view->championshipSlug(),
                    new CalendarEventStepViewColor($presentationToApply->color()),
                    new CalendarEventStepViewIcon($presentationToApply->icon()),
                    clone $view->name(),
                    clone $view->venueLabel(),
                    clone $view->type(),
                    clone $view->dateTime(),
                    clone $view->reference(),
                );

                $this->objects[$newView->id()->value()] = $newView;

                unset($idsToUpdate[$key]);
            }
        }
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
