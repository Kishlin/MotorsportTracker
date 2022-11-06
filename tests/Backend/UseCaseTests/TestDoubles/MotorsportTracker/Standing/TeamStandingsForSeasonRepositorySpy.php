<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Standing;

use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventId;
use Kishlin\Backend\MotorsportTracker\Standing\Application\ViewTeamStandingsForSeason\TeamStandingsForSeasonGateway;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\View\JsonableStandingsView;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\EventRepositorySpy;

final class TeamStandingsForSeasonRepositorySpy implements TeamStandingsForSeasonGateway
{
    public function __construct(
        private TeamStandingRepositorySpy $teamStandingRepositorySpy,
        private EventRepositorySpy $eventRepositorySpy,
    ) {
    }

    public function view(string $seasonId): JsonableStandingsView
    {
        /** @var array{index: int, rankee: string, points: float}[] $standings */
        $standings = [];

        /** @var array<string, boolean> $eventIsInSeason */
        $eventIsInSeason = [];

        foreach ($this->teamStandingRepositorySpy->all() as $teamStanding) {
            $eventId = $teamStanding->eventId()->value();
            $event   = $this->eventRepositorySpy->get(new EventId($eventId));
            assert(null !== $event);

            if (false === array_key_exists($teamStanding->eventId()->value(), $eventIsInSeason)) {
                $eventIsInSeason[$eventId] = $event->seasonId()->value() === $seasonId;
            }

            if (false === $eventIsInSeason[$eventId]) {
                continue;
            }

            $standings[] = [
                'index'  => $event->index()->value(),
                'rankee' => $teamStanding->teamId()->value(),
                'points' => $teamStanding->points()->value(),
            ];
        }

        return JsonableStandingsView::fromSource($standings);
    }
}
