<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\Team;
use Kishlin\Backend\MotorsportTracker\Team\Domain\Gateway\TeamGateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepository;

final class TeamRepositoryUsingDoctrine extends CoreRepository implements TeamGateway
{
    public function save(Team $team): void
    {
        parent::persist($team);
    }
}
