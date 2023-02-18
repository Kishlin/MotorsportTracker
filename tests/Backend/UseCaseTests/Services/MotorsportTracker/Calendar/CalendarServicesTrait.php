<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Calendar;

use Kishlin\Backend\MotorsportTracker\Calendar\Application\CreateViewOnEventStepCreation\CreateViewOnEventStepCreationEventHandler;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation\UpdateViewsAfterAChampionshipPresentationCreationHandler;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Calendar\CalendarEventStepDataRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Calendar\CalendarEventStepViewRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Calendar\EventStepViewDataRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\ChampionshipPresentationRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\ChampionshipRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\SeasonRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\EventRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\EventStepRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\StepTypeRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Venue\VenueRepositorySpy;

trait CalendarServicesTrait
{
    private ?CalendarEventStepViewRepositorySpy $calendarEventStepViewRepositorySpy = null;

    private ?UpdateViewsAfterAChampionshipPresentationCreationHandler $updateViewsAfterAChampionshipPresentationCreationHandler = null;

    private ?EventStepViewDataRepositorySpy $eventStepViewDataRepositorySpy = null;

    private ?CalendarEventStepDataRepositorySpy $calendarEventStepDataRepositorySpy = null;

    private ?CreateViewOnEventStepCreationEventHandler $createViewOnEventStepCreationEventHandler = null;

    abstract public function uuidGenerator(): UuidGenerator;

    abstract public function eventStepRepositorySpy(): EventStepRepositorySpy;

    abstract public function eventRepositorySpy(): EventRepositorySpy;

    abstract public function stepTypeRepositorySpy(): StepTypeRepositorySpy;

    abstract public function venueRepositorySpy(): VenueRepositorySpy;

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

    public function eventStepViewDataRepositorySpy(): EventStepViewDataRepositorySpy
    {
        if (null === $this->eventStepViewDataRepositorySpy) {
            $this->eventStepViewDataRepositorySpy = new EventStepViewDataRepositorySpy(
                $this->eventStepRepositorySpy(),
                $this->eventRepositorySpy(),
                $this->stepTypeRepositorySpy(),
                $this->venueRepositorySpy(),
                $this->seasonRepositorySpy(),
                $this->championshipRepositorySpy(),
                $this->championshipPresentationRepositorySpy(),
            );
        }

        return $this->eventStepViewDataRepositorySpy;
    }

    public function calendarEventStepDataRepositorySpy(): CalendarEventStepDataRepositorySpy
    {
        if (null === $this->calendarEventStepDataRepositorySpy) {
            $this->calendarEventStepDataRepositorySpy = new CalendarEventStepDataRepositorySpy(
                $this->championshipPresentationRepositorySpy(),
                $this->calendarEventStepViewRepositorySpy(),
                $this->championshipRepositorySpy(),
            );
        }

        return $this->calendarEventStepDataRepositorySpy;
    }

    public function updateViewsAfterAChampionshipPresentationCreationHandler(): UpdateViewsAfterAChampionshipPresentationCreationHandler
    {
        if (null === $this->updateViewsAfterAChampionshipPresentationCreationHandler) {
            $this->updateViewsAfterAChampionshipPresentationCreationHandler = new UpdateViewsAfterAChampionshipPresentationCreationHandler(
                $this->calendarEventStepDataRepositorySpy(),
                $this->calendarEventStepDataRepositorySpy(),
                $this->calendarEventStepViewRepositorySpy(),
            );
        }

        return $this->updateViewsAfterAChampionshipPresentationCreationHandler;
    }

    public function createViewOnEventStepCreationEventHandler(): CreateViewOnEventStepCreationEventHandler
    {
        if (null === $this->createViewOnEventStepCreationEventHandler) {
            $this->createViewOnEventStepCreationEventHandler = new CreateViewOnEventStepCreationEventHandler(
                $this->calendarEventStepViewRepositorySpy(),
                $this->eventStepViewDataRepositorySpy(),
                $this->uuidGenerator(),
            );
        }

        return $this->createViewOnEventStepCreationEventHandler;
    }
}
