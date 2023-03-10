<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamIfNotExists;

use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\Team;

interface SaveTeamGateway
{
    public function save(Team $team): void;
}
