<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Application\CreateConstructorTeamIfNotExists;

use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\ConstructorTeam;

interface SaveConstructorTeamGateway
{
    public function save(ConstructorTeam $constructorTeam): void;
}
