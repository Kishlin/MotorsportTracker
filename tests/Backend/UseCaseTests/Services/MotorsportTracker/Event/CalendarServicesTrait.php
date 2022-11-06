<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Event;

use Kishlin\Backend\MotorsportTracker\Event\Application\ViewCalendar\ViewCalendarQueryHandler;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\SeasonRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\CalendarViewRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\EventRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\EventStepRepositorySpy;

trait CalendarServicesTrait
{
    private ?ViewCalendarQueryHandler $viewCalendarQueryHandler = null;

    private ?CalendarViewRepositorySpy $calendarViewRepositorySpy = null;

    abstract public function eventRepositorySpy(): EventRepositorySpy;

    abstract public function seasonRepositorySpy(): SeasonRepositorySpy;

    abstract public function eventStepRepositorySpy(): EventStepRepositorySpy;

    public function viewCalendarQueryHandler(): ViewCalendarQueryHandler
    {
        if (null === $this->viewCalendarQueryHandler) {
            $this->viewCalendarQueryHandler = new ViewCalendarQueryHandler(
                $this->calendarViewRepositorySpy(),
            );
        }

        return $this->viewCalendarQueryHandler;
    }

    public function calendarViewRepositorySpy(): CalendarViewRepositorySpy
    {
        if (null === $this->calendarViewRepositorySpy) {
            $this->calendarViewRepositorySpy = new CalendarViewRepositorySpy(
                $this->eventRepositorySpy(),
                $this->seasonRepositorySpy(),
                $this->eventStepRepositorySpy(),
            );
        }

        return $this->calendarViewRepositorySpy;
    }
}
