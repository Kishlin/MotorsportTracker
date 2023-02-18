<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation;

interface NewPresentationApplierGateway
{
    public function applyDataToViews(CalendarViewsToUpdate $viewsToUpdate, PresentationToApply $presentationToApply): void;
}
