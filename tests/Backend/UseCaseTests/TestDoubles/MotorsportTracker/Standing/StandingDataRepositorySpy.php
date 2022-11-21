<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Standing;

use Kishlin\Backend\MotorsportTracker\Car\Domain\Entity\Car;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\Event;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\EventStep;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepId;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\Entity\Racer;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerId;
use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\Result;
use Kishlin\Backend\MotorsportTracker\Standing\Application\RefreshStandingsOnResultsRecorded\StandingDataDTO;
use Kishlin\Backend\MotorsportTracker\Standing\Application\RefreshStandingsOnResultsRecorded\StandingDataReader;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Car\CarRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\EventRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\EventStepRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Racer\RacerRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Result\ResultRepositorySpy;

final class StandingDataRepositorySpy implements StandingDataReader
{
    public function __construct(
        private EventStepRepositorySpy $eventStepRepositorySpy,
        private ResultRepositorySpy $resultRepositorySpy,
        private RacerRepositorySpy $racerRepositorySpy,
        private EventRepositorySpy $eventRepositorySpy,
        private CarRepositorySpy $carRepositorySpy,
    ) {
    }

    /**
     * @return StandingDataDTO[]
     */
    public function findStandingDataForEvent(string $eventId): array
    {
        $referenceEvent = $this->getEvent($eventId);

        /** @var array<string, boolean> $memoizedEventStepData */
        $memoizedEventStepData = [];

        /** @var StandingDataDTO[] $standings */
        $standings = [];

        foreach ($this->resultRepositorySpy->all() as $result) {
            $eventStepId = $result->eventStepId()->value();

            if (false === array_key_exists($eventStepId, $memoizedEventStepData)) {
                $memoizedEventStepData[$eventStepId] = $this->isEventStepPartOfSameChampionship(
                    $eventStepId,
                    $referenceEvent,
                );
            }

            if (false === $memoizedEventStepData[$eventStepId]) {
                continue;
            }

            $racer = $this->getRacer($result);
            $car   = $this->getCar($racer);

            $driverId = $racer->driverId()->value();
            $teamId   = $car->teamId()->value();

            $standings[] = StandingDataDTO::fromScalars($driverId, $teamId, $result->points()->value());
        }

        return $standings;
    }

    private function getEvent(string $eventId): Event
    {
        $event = $this->eventRepositorySpy->get(new EventId($eventId));
        assert($event instanceof Event);

        return $event;
    }

    private function isEventStepPartOfSameChampionship(string $eventStepId, Event $referenceEvent): bool
    {
        $eventStep = $this->eventStepRepositorySpy->get(new EventStepId($eventStepId));
        assert($eventStep instanceof EventStep);

        $event = $this->eventRepositorySpy->get(EventId::fromOther($eventStep->eventId()));
        assert($event instanceof Event);

        return $event->seasonId()->equals($referenceEvent->seasonId())
            && $event->index()->value() <= $referenceEvent->index()->value();
    }

    private function getRacer(Result $result): Racer
    {
        $racer = $this->racerRepositorySpy->get(RacerId::fromOther($result->racerId()));
        assert($racer instanceof Racer);

        return $racer;
    }

    private function getCar(Racer $racer): Car
    {
        $car = $this->carRepositorySpy->get(CarId::fromOther($racer->carId()));
        assert($car instanceof Car);

        return $car;
    }
}
