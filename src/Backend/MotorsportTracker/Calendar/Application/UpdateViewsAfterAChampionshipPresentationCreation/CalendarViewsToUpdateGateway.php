<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation;

use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface CalendarViewsToUpdateGateway
{
    public function findForPresentation(UuidValueObject $presentationId): CalendarViewsToUpdate;
}
