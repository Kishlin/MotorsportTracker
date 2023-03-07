<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportCache\Calendar;

use Kishlin\Backend\MotorsportCache\Calendar\Application\SyncCalendarEvents\SyncCalendarEventsCommandHandler;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Calendar\CalendarEventRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Calendar\FindEventsRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Calendar\FindSeriesRepositorySpy;

trait CalendarServicesTrait
{
    private ?FindEventsRepositorySpy $findEventsRepositorySpy = null;

    private ?FindSeriesRepositorySpy $findSeriesRepositorySpy = null;

    private ?CalendarEventRepositorySpy $calendarEventRepositorySpy = null;

    private ?SyncCalendarEventsCommandHandler $syncCalendarEventsCommandHandler = null;

    public function findEventsRepositorySpy(): FindEventsRepositorySpy
    {
        if (null === $this->findEventsRepositorySpy) {
            $this->findEventsRepositorySpy = new FindEventsRepositorySpy(
                $this->sessionTypeRepositorySpy(),
                $this->eventSessionRepositorySpy(),
                $this->countryRepositorySpy(),
                $this->championshipRepositorySpy(),
                $this->eventRepositorySpy(),
                $this->seasonRepositorySpy(),
                $this->venueRepositorySpy(),
            );
        }

        return $this->findEventsRepositorySpy;
    }

    public function findSeriesRepositorySpy(): FindSeriesRepositorySpy
    {
        if (null === $this->findSeriesRepositorySpy) {
            $this->findSeriesRepositorySpy = new FindSeriesRepositorySpy(
                $this->championshipPresentationRepositorySpy(),
                $this->championshipRepositorySpy(),
                $this->seasonRepositorySpy(),
            );
        }

        return $this->findSeriesRepositorySpy;
    }

    public function calendarEventRepositorySpy(): CalendarEventRepositorySpy
    {
        if (null === $this->calendarEventRepositorySpy) {
            $this->calendarEventRepositorySpy = new CalendarEventRepositorySpy();
        }

        return $this->calendarEventRepositorySpy;
    }

    public function syncCalendarEventsCommandHandler(): SyncCalendarEventsCommandHandler
    {
        if (null === $this->syncCalendarEventsCommandHandler) {
            $this->syncCalendarEventsCommandHandler = new SyncCalendarEventsCommandHandler(
                $this->calendarEventRepositorySpy(),
                $this->findSeriesRepositorySpy(),
                $this->findEventsRepositorySpy(),
                $this->eventDispatcher(),
                $this->uuidGenerator(),
            );
        }

        return $this->syncCalendarEventsCommandHandler;
    }
}
