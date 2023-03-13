<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Repository\CreateTeamIfNotExists;

use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamIfNotExists\SaveTeamGateway;
use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\Team;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class SaveTeamRepositoryUsingDoctrine extends CoreRepository implements SaveTeamGateway
{
    public function save(Team $team): void
    {
        parent::persist($team);
    }
}
