<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\DomainEvent\TeamParticipationCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\TeamParticipationId;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\TeamParticipationSeasonId;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\TeamParticipationTeamId;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;

final class TeamParticipation extends AggregateRoot
{
    private function __construct(
        private TeamParticipationId $id,
        private TeamParticipationSeasonId $seasonId,
        private TeamParticipationTeamId $teamId,
    ) {
    }

    public static function create(
        TeamParticipationId $id,
        TeamParticipationSeasonId $seasonId,
        TeamParticipationTeamId $teamId,
    ): self {
        $teamParticipation = new self($id, $seasonId, $teamId);

        $teamParticipation->record(new TeamParticipationCreatedDomainEvent($id));

        return $teamParticipation;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        TeamParticipationId $id,
        TeamParticipationTeamId $teamId,
        TeamParticipationSeasonId $seasonId,
    ): self {
        return new self($id, $seasonId, $teamId);
    }

    public function id(): TeamParticipationId
    {
        return $this->id;
    }

    public function teamId(): TeamParticipationTeamId
    {
        return $this->teamId;
    }

    public function seasonId(): TeamParticipationSeasonId
    {
        return $this->seasonId;
    }
}
