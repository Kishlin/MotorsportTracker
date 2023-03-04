<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportCache\Calendar;

use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewCalendar\ViewCalendarQueryHandler;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Calendar\CalendarEventStepViewRepositorySpy;

trait CalendarServicesTrait
{
    private ?CalendarEventStepViewRepositorySpy $calendarEventStepViewRepositorySpy = null;

    private ?ViewCalendarQueryHandler $viewCalendarQueryHandler = null;

    public function calendarEventStepViewRepositorySpy(): CalendarEventStepViewRepositorySpy
    {
        if (null === $this->calendarEventStepViewRepositorySpy) {
            $this->calendarEventStepViewRepositorySpy = new CalendarEventStepViewRepositorySpy();
        }

        return $this->calendarEventStepViewRepositorySpy;
    }

    public function viewCalendarQueryHandler(): ViewCalendarQueryHandler
    {
        if (null === $this->viewCalendarQueryHandler) {
            $this->viewCalendarQueryHandler = new ViewCalendarQueryHandler(
                $this->calendarEventStepViewRepositorySpy(),
            );
        }

        return $this->viewCalendarQueryHandler;
    }
}
