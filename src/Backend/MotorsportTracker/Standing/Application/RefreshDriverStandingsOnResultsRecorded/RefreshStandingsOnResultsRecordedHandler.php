<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Application\RefreshDriverStandingsOnResultsRecorded;

use Kishlin\Backend\MotorsportTracker\Result\Application\RecordResults\ResultsRecordedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\DriverStanding;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\TeamStanding;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\Gateway\DriverStandingGateway;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\Gateway\TeamStandingGateway;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingDriverId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingEventId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\DriverStandingPoints;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\TeamStandingEventId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\TeamStandingId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\TeamStandingPoints;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\TeamStandingTeamId;
use Kishlin\Backend\Shared\Domain\Bus\Event\DomainEventSubscriber;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;

final class RefreshStandingsOnResultsRecordedHandler implements DomainEventSubscriber
{
    public function __construct(
        private EventIdOfEventStepIdReader $eventIdForEventStepIdRepositorySpy,
        private DriverStandingGateway $driverStandingGateway,
        private TeamStandingGateway $teamStandingGateway,
        private StandingDataReader $standingDataGateway,
        private UuidGenerator $uuidGenerator,
    ) {
    }

    public function __invoke(ResultsRecordedDomainEvent $domainEvent): void
    {
        $eventId      = $this->eventIdForEventStepIdRepositorySpy->eventIdForEventStepId($domainEvent->eventStepId());
        $standingData = $this->standingDataGateway->findStandingDataForEvent($eventId);

        /** @var array<string, float> $teamStandings */
        $teamStandings = [];

        foreach ($standingData as $standingDataDTO) {
            $newDriverTotal = $standingDataDTO->pointsUntilEvent();

            if (array_key_exists($standingDataDTO->teamId(), $teamStandings)) {
                $teamStandings[$standingDataDTO->teamId()] += $newDriverTotal;
            } else {
                $teamStandings[$standingDataDTO->teamId()] = $newDriverTotal;
            }

            $this->saveDriverStanding(
                new DriverStandingEventId($eventId),
                new DriverStandingDriverId($standingDataDTO->driverId()),
                new DriverStandingPoints($newDriverTotal),
            );
        }

        foreach ($teamStandings as $teamId => $newTeamTotal) {
            $this->saveTeamStanding(
                new TeamStandingEventId($eventId),
                new TeamStandingTeamId($teamId),
                new TeamStandingPoints($newTeamTotal),
            );
        }
    }

    public static function subscribedTo(): array
    {
        return [
            ResultsRecordedDomainEvent::class,
        ];
    }

    private function saveDriverStanding(
        DriverStandingEventId $eventId,
        DriverStandingDriverId $driverId,
        DriverStandingPoints $newDriverTotal,
    ): void {
        $existing = $this->driverStandingGateway->find($driverId, $eventId);

        if (null === $existing) {
            $id = new DriverStandingId($this->uuidGenerator->uuid4());

            $driverStanding = DriverStanding::create($id, $eventId, $driverId, $newDriverTotal);

            $this->driverStandingGateway->save($driverStanding);
        } else {
            $existing->updateScore($newDriverTotal);

            $this->driverStandingGateway->save($existing);
        }
    }

    private function saveTeamStanding(
        TeamStandingEventId $eventId,
        TeamStandingTeamId $teamId,
        TeamStandingPoints $newTeamTotal,
    ): void {
        $existing = $this->teamStandingGateway->find($teamId, $eventId);

        if (null === $existing) {
            $id = new TeamStandingId($this->uuidGenerator->uuid4());

            $teamStanding = TeamStanding::create($id, $eventId, $teamId, $newTeamTotal);

            $this->teamStandingGateway->save($teamStanding);
        } else {
            $existing->updateScore($newTeamTotal);

            $this->teamStandingGateway->save($existing);
        }
    }
}
