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

            $this->saveDriverStanding($eventId, $standingDataDTO->driverId(), $newDriverTotal);
        }

        foreach ($teamStandings as $teamId => $newTeamTotal) {
            $this->saveTeamStanding($eventId, $teamId, $newTeamTotal);
        }
    }

    public static function subscribedTo(): array
    {
        return [
            ResultsRecordedDomainEvent::class,
        ];
    }

    private function saveDriverStanding(
        string $eventId,
        string $driverId,
        float $newDriverTotal,
    ): void {
        $driverStanding = DriverStanding::create(
            new DriverStandingId($this->uuidGenerator->uuid4()),
            new DriverStandingEventId($eventId),
            new DriverStandingDriverId($driverId),
            new DriverStandingPoints($newDriverTotal),
        );

        $this->driverStandingGateway->save($driverStanding);
    }

    private function saveTeamStanding(
        string $eventId,
        string $teamId,
        float $newTeamTotal,
    ): void {
        $teamStanding = TeamStanding::create(
            new TeamStandingId($this->uuidGenerator->uuid4()),
            new TeamStandingEventId($eventId),
            new TeamStandingTeamId($teamId),
            new TeamStandingPoints($newTeamTotal),
        );

        $this->teamStandingGateway->save($teamStanding);
    }
}
