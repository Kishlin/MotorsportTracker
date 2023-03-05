<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Doctrine\Repository\CreateTeam;

use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeam\SaveTeamGateway;
use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\Team;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepository;

final class SaveTeamRepositoryUsingDoctrine extends CoreRepository implements SaveTeamGateway
{
    public function save(Team $team): void
    {
        parent::persist($team);
    }
}
