<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\TeamStandingEventId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\TeamStandingId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\TeamStandingPoints;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\TeamStandingTeamId;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;

final class TeamStanding extends AggregateRoot
{
    private function __construct(
        private TeamStandingId $id,
        private TeamStandingEventId $eventId,
        private TeamStandingTeamId $teamId,
        private TeamStandingPoints $points,
    ) {
    }

    public static function create(
        TeamStandingId $id,
        TeamStandingEventId $eventId,
        TeamStandingTeamId $teamId,
        TeamStandingPoints $points,
    ): self {
        return new self($id, $eventId, $teamId, $points);
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        TeamStandingId $id,
        TeamStandingEventId $eventId,
        TeamStandingTeamId $teamId,
        TeamStandingPoints $points,
    ): self {
        return new self($id, $eventId, $teamId, $points);
    }

    public function updateScore(TeamStandingPoints $newTeamTotal): void
    {
        $this->points = $newTeamTotal;
    }

    public function id(): TeamStandingId
    {
        return $this->id;
    }

    public function eventId(): TeamStandingEventId
    {
        return $this->eventId;
    }

    public function teamId(): TeamStandingTeamId
    {
        return $this->teamId;
    }

    public function points(): TeamStandingPoints
    {
        return $this->points;
    }
}
