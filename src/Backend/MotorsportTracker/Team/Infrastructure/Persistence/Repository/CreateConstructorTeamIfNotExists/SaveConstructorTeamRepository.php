<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Repository\CreateConstructorTeamIfNotExists;

use Kishlin\Backend\MotorsportTracker\Team\Application\CreateConstructorTeamIfNotExists\SaveConstructorTeamGateway;
use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\ConstructorTeam;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class SaveConstructorTeamRepository extends CoreRepository implements SaveConstructorTeamGateway
{
    public function save(ConstructorTeam $constructorTeam): void
    {
        $this->persist($constructorTeam);
    }
}
