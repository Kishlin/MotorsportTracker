<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation;

interface CalendarViewsToUpdateGateway
{
    public function findForSlug(string $slug): CalendarViewsToUpdate;
}
