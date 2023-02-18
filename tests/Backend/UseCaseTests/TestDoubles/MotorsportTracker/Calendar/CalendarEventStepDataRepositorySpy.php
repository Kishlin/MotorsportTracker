<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Calendar;

use Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation\CalendarViewsToUpdate;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation\CalendarViewsToUpdateGateway;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation\PresentationToApply;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation\PresentationToApplyGateway;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\ChampionshipPresentation;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\ChampionshipPresentationRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\ChampionshipRepositorySpy;

final class CalendarEventStepDataRepositorySpy implements CalendarViewsToUpdateGateway, PresentationToApplyGateway
{
    /**
     * @var array<string, ChampionshipPresentation>
     */
    private array $memoizedPresentations = [];

    public function __construct(
        private readonly ChampionshipPresentationRepositorySpy $championshipPresentationRepositorySpy,
        private readonly CalendarEventStepViewRepositorySpy $calendarEventStepViewRepositorySpy,
        private readonly ChampionshipRepositorySpy $championshipRepositorySpy,
    ) {
    }

    public function findForPresentation(UuidValueObject $presentationId): CalendarViewsToUpdate
    {
        $championshipSlug = $this
            ->championshipRepositorySpy
            ->safeGet($this->presentationForId($presentationId)->championshipId())
            ->slug()
        ;

        $idList = [];
        foreach ($this->calendarEventStepViewRepositorySpy->all() as $calendarEventStepView) {
            if (false === $championshipSlug->equals($calendarEventStepView->championshipSlug())) {
                continue;
            }

            $idList[] = $calendarEventStepView->id()->value();
        }

        return CalendarViewsToUpdate::fromScalars($idList);
    }

    public function findData(UuidValueObject $presentationId): PresentationToApply
    {
        $presentation = $this->presentationForId($presentationId);

        return PresentationToApply::fromScalars($presentation->color()->value(), $presentation->icon()->value());
    }

    private function presentationForId(UuidValueObject $uuidValueObject): ChampionshipPresentation
    {
        $presentationId = $uuidValueObject->value();

        if (false === array_key_exists($presentationId, $this->memoizedPresentations)) {
            $this->memoizedPresentations[$presentationId] = $this->championshipPresentationRepositorySpy->safeGet(
                $uuidValueObject,
            );
        }

        return $this->memoizedPresentations[$presentationId];
    }
}
