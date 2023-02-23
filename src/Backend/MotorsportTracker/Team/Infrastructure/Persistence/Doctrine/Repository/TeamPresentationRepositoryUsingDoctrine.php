<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamPresentation\SaveTeamPresentationGateway;
use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\TeamPresentation;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class TeamPresentationRepositoryUsingDoctrine extends DoctrineRepository implements SaveTeamPresentationGateway
{
    public function save(TeamPresentation $teamPresentation): void
    {
        $this->persist($teamPresentation);
    }
}
