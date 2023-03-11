<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Doctrine\Repository\CreateTeamIfNotExists;

use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamIfNotExists\SaveTeamGateway;
use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\Team;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepositoryLegacy;

final class SaveTeamRepositoryUsingDoctrine extends CoreRepositoryLegacy implements SaveTeamGateway
{
    public function save(Team $team): void
    {
        parent::persist($team);
    }
}
