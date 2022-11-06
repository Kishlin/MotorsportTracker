<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Domain\Gateway;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\TeamStanding;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\TeamStandingEventId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\TeamStandingTeamId;

interface TeamStandingGateway
{
    public function save(TeamStanding $teamStanding): void;

    public function find(TeamStandingTeamId $teamId, TeamStandingEventId $eventId): ?TeamStanding;
}
