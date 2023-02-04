<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Calendar;

use Kishlin\Backend\MotorsportTracker\Calendar\Application\CreateViewOnEventStepCreation\EventStepViewData;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\CreateViewOnEventStepCreation\EventStepViewDataGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\ChampionshipPresentationRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\ChampionshipRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\SeasonRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\EventRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\EventStepRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\StepTypeRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Venue\VenueRepositorySpy;

final class EventStepViewDataRepositorySpy implements EventStepViewDataGateway
{
    public function __construct(
        private readonly EventStepRepositorySpy $eventStepRepositorySpy,
        private readonly EventRepositorySpy $eventRepositorySpy,
        private readonly StepTypeRepositorySpy $stepTypeRepositorySpy,
        private readonly VenueRepositorySpy $venueRepositorySpy,
        private readonly SeasonRepositorySpy $seasonRepositorySpy,
        private readonly ChampionshipRepositorySpy $championshipRepositorySpy,
        private readonly ChampionshipPresentationRepositorySpy $championshipPresentationRepositorySpy,
    ) {
    }

    public function find(UuidValueObject $eventStepId): ?EventStepViewData
    {
        $eventStep = $this->eventStepRepositorySpy->get($eventStepId);

        if (null === $eventStep) {
            return null;
        }

        $event        = $this->eventRepositorySpy->safeGet($eventStep->eventId());
        $step         = $this->stepTypeRepositorySpy->safeGet($eventStep->typeId());
        $venue        = $this->venueRepositorySpy->safeGet($event->venueId());
        $season       = $this->seasonRepositorySpy->safeGet($event->seasonId());
        $championship = $this->championshipRepositorySpy->safeGet($season->championshipId());
        $presentation = $this->championshipPresentationRepositorySpy->latest($championship->id());

        assert(null !== $presentation);

        return EventStepViewData::fromScalars(
            $championship->slug()->value(),
            $presentation->color()->value(),
            $presentation->icon()->value(),
            $event->label()->value(),
            $step->label()->value(),
            $venue->name()->value(),
            $eventStep->dateTime()->value(),
        );
    }
}
