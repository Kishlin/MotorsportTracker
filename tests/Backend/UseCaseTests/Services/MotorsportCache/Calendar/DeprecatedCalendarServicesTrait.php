<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportCache\Calendar;

use Kishlin\Backend\MotorsportCache\Calendar\Application\DeprecatedViewCalendar\ViewCalendarQueryHandler;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Calendar\DeprecatedCalendarEventStepViewRepositorySpy;

trait DeprecatedCalendarServicesTrait
{
    private ?DeprecatedCalendarEventStepViewRepositorySpy $calendarEventStepViewRepositorySpy = null;

    private ?ViewCalendarQueryHandler $viewCalendarQueryHandler = null;

    public function calendarEventStepViewRepositorySpy(): DeprecatedCalendarEventStepViewRepositorySpy
    {
        if (null === $this->calendarEventStepViewRepositorySpy) {
            $this->calendarEventStepViewRepositorySpy = new DeprecatedCalendarEventStepViewRepositorySpy();
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
