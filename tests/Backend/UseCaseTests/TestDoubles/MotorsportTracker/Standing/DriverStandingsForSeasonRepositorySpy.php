<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Standing;

use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventId;
use Kishlin\Backend\MotorsportTracker\Standing\Application\ViewDriverStandingsForSeason\DriverStandingsForSeasonGateway;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\View\JsonableDriverStandingsView;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\EventRepositorySpy;

final class DriverStandingsForSeasonRepositorySpy implements DriverStandingsForSeasonGateway
{
    public function __construct(
        private DriverStandingRepositorySpy $driverStandingRepositorySpy,
        private EventRepositorySpy $eventRepositorySpy,
    ) {
    }

    public function view(string $seasonId): JsonableDriverStandingsView
    {
        /** @var array{index: int, driverId: string, points: float}[] $standings */
        $standings = [];

        /** @var array<string, boolean> $eventIsInSeason */
        $eventIsInSeason = [];

        foreach ($this->driverStandingRepositorySpy->all() as $driverStanding) {
            $eventId = $driverStanding->eventId()->value();
            $event   = $this->eventRepositorySpy->get(new EventId($eventId));
            assert(null !== $event);

            if (false === array_key_exists($driverStanding->eventId()->value(), $eventIsInSeason)) {
                $eventIsInSeason[$eventId] = $event->seasonId()->value() === $seasonId;
            }

            if (false === $eventIsInSeason[$eventId]) {
                continue;
            }

            $standings[] = [
                'index'    => $event->index()->value(),
                'driverId' => $driverStanding->driverId()->value(),
                'points'   => $driverStanding->points()->value(),
            ];
        }

        return JsonableDriverStandingsView::fromSource($standings);
    }
}
