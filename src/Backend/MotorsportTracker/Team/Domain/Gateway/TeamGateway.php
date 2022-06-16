<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Domain\Gateway;

use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\Team;

interface TeamGateway
{
    public function save(Team $team): void;
}
