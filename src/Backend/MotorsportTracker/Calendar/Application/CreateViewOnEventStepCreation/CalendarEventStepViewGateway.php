<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Calendar\Application\CreateViewOnEventStepCreation;

use Kishlin\Backend\MotorsportTracker\Calendar\Domain\Entity\CalendarEventStepView;

interface CalendarEventStepViewGateway
{
    public function save(CalendarEventStepView $calendarEventStepView): void;
}
