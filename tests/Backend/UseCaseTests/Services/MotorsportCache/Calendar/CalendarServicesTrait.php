<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportCache\Calendar;

use Kishlin\Backend\MotorsportCache\Calendar\Application\ViewCalendar\ViewCalendarQueryHandler;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Calendar\CalendarEventStepViewRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\ChampionshipPresentationRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\ChampionshipRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\SeasonRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\EventRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\EventStepRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\StepTypeRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Venue\SaveVenueRepositorySpy;

trait CalendarServicesTrait
{
    private ?CalendarEventStepViewRepositorySpy $calendarEventStepViewRepositorySpy = null;

    private ?ViewCalendarQueryHandler $viewCalendarQueryHandler = null;

    abstract public function uuidGenerator(): UuidGenerator;

    abstract public function eventStepRepositorySpy(): EventStepRepositorySpy;

    abstract public function eventRepositorySpy(): EventRepositorySpy;

    abstract public function stepTypeRepositorySpy(): StepTypeRepositorySpy;

    abstract public function venueRepositorySpy(): SaveVenueRepositorySpy;

    abstract public function seasonRepositorySpy(): SeasonRepositorySpy;

    abstract public function championshipRepositorySpy(): ChampionshipRepositorySpy;

    abstract public function championshipPresentationRepositorySpy(): ChampionshipPresentationRepositorySpy;

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
