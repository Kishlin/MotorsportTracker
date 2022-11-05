<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Standing;

use Kishlin\Backend\MotorsportTracker\Car\Domain\Entity\Car;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\Event;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventId;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\Entity\Racer;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerId;
use Kishlin\Backend\MotorsportTracker\Standing\Application\RefreshDriverStandingsOnResultsRecorded\StandingDataDTO;
use Kishlin\Backend\MotorsportTracker\Standing\Application\RefreshDriverStandingsOnResultsRecorded\StandingDataReader;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Car\CarRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\EventRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\EventStepRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Racer\RacerRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Result\ResultRepositorySpy;

final class StandingDataRepositorySpy implements StandingDataReader
{
    public function __construct(
        private DriverStandingRepositorySpy $driverStandingRepositorySpy,
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
        /** @var StandingDataDTO[] $standingDataList */
        $standingDataList = [];

        $referenceEvent  = $this->getEvent($eventId);
        $previousEventId = $this->getPreviousEventId($referenceEvent);

        $pointPerRacer = $this->getPointsPerRacerForEvent($referenceEvent);

        if (null !== $previousEventId) {
            $previousStandingPerRacer = $this->getPreviousStandingsPerDriver($previousEventId);
        }

        foreach ($pointPerRacer as $racerId => $points) {
            $racer = $this->racerRepositorySpy->get(new RacerId($racerId));
            assert($racer instanceof Racer);

            $car = $this->carRepositorySpy->get(CarId::fromOther($racer->carId()));
            assert($car instanceof Car);

            $driverId = $racer->driverId()->value();
            $teamId   = $car->teamId()->value();

            $pointsUntilPreviousEvent = $previousStandingPerRacer[$driverId] ?? 0.0;

            $standingDataList[] = StandingDataDTO::fromScalars($driverId, $teamId, $pointsUntilPreviousEvent, $points);
        }

        return $standingDataList;
    }

    private function getEvent(string $eventId): Event
    {
        $event = $this->eventRepositorySpy->get(new EventId($eventId));
        assert($event instanceof Event);

        return $event;
    }

    private function getPreviousEventId(Event $reference): ?EventId
    {
        foreach ($this->eventRepositorySpy->all() as $event) {
            if ($event->seasonId()->equals($reference->seasonId())
                && ($reference->index()->value() - 1) === $event->index()->value()
            ) {
                return $event->id();
            }
        }

        return null;
    }

    /**
     * @return array<string, float>
     */
    private function getPointsPerRacerForEvent(Event $event): array
    {
        $pointsPerRacer = [];

        $eventStepIds = $this->getAllEventStepIdsForEvent($event);

        foreach ($this->resultRepositorySpy->all() as $result) {
            if (false === in_array($result->eventStepId()->value(), $eventStepIds, true)) {
                continue;
            }

            $racerId = $result->racerId()->value();

            if (array_key_exists($racerId, $pointsPerRacer)) {
                $pointsPerRacer[$racerId] += $result->points()->value();
            } else {
                $pointsPerRacer[$racerId] = $result->points()->value();
            }
        }

        return $pointsPerRacer;
    }

    /**
     * @return string[]
     */
    private function getAllEventStepIdsForEvent(Event $event): array
    {
        $ids = [];

        foreach ($this->eventStepRepositorySpy->all() as $eventStep) {
            if ($eventStep->eventId()->equals($event->id())) {
                $ids[] = $eventStep->id()->value();
            }
        }

        return $ids;
    }

    /**
     * @return array<string, float>
     */
    private function getPreviousStandingsPerDriver(EventId $previousEventId): array
    {
        $pointsPerDriver = [];

        foreach ($this->driverStandingRepositorySpy->all() as $driverStanding) {
            if ($driverStanding->eventId()->equals($previousEventId)) {
                $pointsPerDriver[$driverStanding->driverId()->value()] = $driverStanding->points()->value();
            }
        }

        return $pointsPerDriver;
    }
}
