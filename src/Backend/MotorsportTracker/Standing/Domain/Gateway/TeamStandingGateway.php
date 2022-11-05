<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Domain\Gateway;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\TeamStanding;

interface TeamStandingGateway
{
    public function save(TeamStanding $teamStanding): void;
}
