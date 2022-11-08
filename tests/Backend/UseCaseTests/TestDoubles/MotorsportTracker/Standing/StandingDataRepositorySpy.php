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

        $pointsPerRacer = $this->getPointsPerRacer($referenceEvent);

        return $this->mapPointsPerRacerToStandings($pointsPerRacer);
    }

    private function getEvent(string $eventId): Event
    {
        $event = $this->eventRepositorySpy->get(new EventId($eventId));
        assert($event instanceof Event);

        return $event;
    }

    /**
     * @return array<string, float>
     */
    private function getPointsPerRacer(Event $referenceEvent): array
    {
        /** @var array<string, float> $pointsPerRacer */
        $pointsPerRacer = [];

        /** @var array<string, boolean> $eventStepData */
        $eventStepData = [];

        foreach ($this->resultRepositorySpy->all() as $result) {
            $eventStepId = $result->eventStepId()->value();

            if (false === array_key_exists($eventStepId, $eventStepData)) {
                $eventStep = $this->eventStepRepositorySpy->get(new EventStepId($eventStepId));
                assert($eventStep instanceof EventStep);

                $event = $this->eventRepositorySpy->get(EventId::fromOther($eventStep->eventId()));
                assert($event instanceof Event);

                $eventStepData[$eventStepId] = $event->seasonId()->equals($referenceEvent->seasonId())
                    && $event->index()->value() <= $referenceEvent->index()->value();
            }

            if (false === $eventStepData[$eventStepId]) {
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
     * @param array<string, float> $pointsPerRacer
     *
     * @return StandingDataDTO[]
     */
    private function mapPointsPerRacerToStandings(array $pointsPerRacer): array
    {
        /** @var StandingDataDTO[] $standingDataList */
        $standingDataList = [];

        foreach ($pointsPerRacer as $racerId => $points) {
            $racer = $this->racerRepositorySpy->get(new RacerId($racerId));
            assert($racer instanceof Racer);

            $car = $this->carRepositorySpy->get(CarId::fromOther($racer->carId()));
            assert($car instanceof Car);

            $driverId = $racer->driverId()->value();
            $teamId   = $car->teamId()->value();

            $standingDataList[] = StandingDataDTO::fromScalars($driverId, $teamId, $points);
        }

        return $standingDataList;
    }
}
