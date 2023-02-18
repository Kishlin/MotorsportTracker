<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Calendar;

use Kishlin\Backend\MotorsportTracker\Calendar\Application\CreateViewOnEventStepCreation\CalendarEventStepViewGateway;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation\CalendarViewsToUpdate;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation\NewPresentationApplierGateway;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation\PresentationToApply;
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
final class CalendarEventStepViewRepositorySpy extends AbstractRepositorySpy implements CalendarEventStepViewGateway, NewPresentationApplierGateway
{
    public function save(CalendarEventStepView $calendarEventStepView): void
    {
        $this->add($calendarEventStepView);
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
                );

                $this->objects[$newView->id()->value()] = $newView;

                unset($idsToUpdate[$key]);
            }
        }
    }
}
